import './bootstrap';

const player = document.querySelector('[data-watch-player]');
if (player) {
    const watchableType = player.dataset.watchableType;
    const watchableId = player.dataset.watchableId;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const freeTimeLeftEl = document.querySelector('[data-free-time-left]');
    const accountUrl = player.dataset.accountUrl || '/account';
    const sourceEl = player.querySelector('source');
    const parsedRemaining = Number(player.dataset.remainingSeconds);
    let remainingSeconds = Number.isFinite(parsedRemaining) ? parsedRemaining : null;
    let blocked = false;
    const streamSources = (() => {
        try {
            return JSON.parse(player.dataset.streamSources || '[]');
        } catch (error) {
            return [];
        }
    })();

    let lastSent = 0;
    let progressRequestInFlight = false;

    const updateFreeTimeLabel = () => {
        if (!freeTimeLeftEl) {
            return;
        }

        if (remainingSeconds === null) {
            freeTimeLeftEl.textContent = 'Unlimited';
            return;
        }

        const minutes = Math.max(0, Math.ceil(remainingSeconds / 60));
        freeTimeLeftEl.textContent = `${minutes} min`;
    };

    const handleFreeLimitReached = (redirectUrl = accountUrl) => {
        if (blocked) {
            return;
        }

        blocked = true;
        remainingSeconds = 0;
        updateFreeTimeLabel();
        player.pause();

        if (redirectUrl) {
            window.location.assign(redirectUrl);
        }
    };

    const swapQuality = (source) => {
        if (!source?.url) {
            return;
        }

        const currentTime = player.currentTime || 0;
        const wasPaused = player.paused;

        if (sourceEl) {
            sourceEl.src = source.url;
        }
        player.src = source.url;
        player.load();

        player.addEventListener(
            'loadedmetadata',
            () => {
                if (Number.isFinite(currentTime)) {
                    player.currentTime = currentTime;
                }
                if (!wasPaused) {
                    player.play().catch(() => {});
                }
            },
            { once: true }
        );
    };

    const qualitySelect = document.querySelector('[data-quality-select]');
    if (qualitySelect && streamSources.length) {
        const findSource = (value) => streamSources.find((source) => String(source.id) === String(value));
        const defaultSource = findSource(qualitySelect.value);
        if (defaultSource) {
            swapQuality(defaultSource);
        }

        qualitySelect.addEventListener('change', () => {
            const selected = findSource(qualitySelect.value);
            if (selected) {
                swapQuality(selected);
            }
        });
    }

    updateFreeTimeLabel();

    const sendProgress = (completed = false) => {
        if (blocked || progressRequestInFlight) {
            return;
        }

        const current = Math.floor(player.currentTime || 0);
        if (!completed && current - lastSent < 15) {
            return;
        }

        const progress = player.duration ? Math.floor((current / player.duration) * 100) : 0;
        lastSent = current;

        progressRequestInFlight = true;

        fetch('/watch/progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                watchable_type: watchableType,
                watchable_id: watchableId,
                last_position_seconds: current,
                progress_percent: progress,
                completed,
            }),
        })
            .then(async (response) => {
                let payload = null;

                try {
                    payload = await response.json();
                } catch (error) {
                    payload = null;
                }

                if (!response.ok) {
                    if (response.status === 403 && payload?.status === 'blocked') {
                        handleFreeLimitReached(payload.redirect_url || accountUrl);
                    }

                    return;
                }

                if (typeof payload?.remaining_seconds === 'number') {
                    remainingSeconds = payload.remaining_seconds;
                    updateFreeTimeLabel();

                    if (remainingSeconds <= 0) {
                        handleFreeLimitReached(payload.redirect_url || accountUrl);
                    }
                }
            })
            .catch(() => {})
            .finally(() => {
                progressRequestInFlight = false;
            });
    };

    player.addEventListener('timeupdate', () => sendProgress(false));
    player.addEventListener('ended', () => sendProgress(true));
}

const previewCards = document.querySelectorAll('[data-preview-card]');
if (previewCards.length > 0) {
    previewCards.forEach((card) => {
        const endpoint = card.dataset.previewEndpoint;
        const video = card.querySelector('[data-preview-video]');

        if (!endpoint || !video) {
            return;
        }

        let hoverActive = false;
        let activeRequest = 0;

        const stopPreview = () => {
            hoverActive = false;
            activeRequest += 1;

            video.pause();
            video.removeAttribute('src');
            video.load();
            card.classList.remove('is-previewing');
        };

        const startPreview = async () => {
            hoverActive = true;
            activeRequest += 1;
            const requestId = activeRequest;

            try {
                const response = await fetch(endpoint, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!response.ok) {
                    return;
                }

                const payload = await response.json();
                if (!hoverActive || requestId !== activeRequest || !payload?.url) {
                    return;
                }

                video.src = payload.url;
                video.currentTime = 0;
                await video.play().catch(() => {});

                if (!video.paused && hoverActive) {
                    card.classList.add('is-previewing');
                }
            } catch (error) {
                // Ignore preview errors and keep the poster visible.
            }
        };

        card.addEventListener('mouseenter', startPreview);
        card.addEventListener('focusin', startPreview);
        card.addEventListener('mouseleave', stopPreview);
        card.addEventListener('focusout', (event) => {
            if (!card.contains(event.relatedTarget)) {
                stopPreview();
            }
        });
    });
}
