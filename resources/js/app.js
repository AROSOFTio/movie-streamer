import './bootstrap';

const player = document.querySelector('[data-watch-player]');
if (player) {
    const watchableType = player.dataset.watchableType;
    const watchableId = player.dataset.watchableId;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const sourceEl = player.querySelector('source');
    const streamSources = (() => {
        try {
            return JSON.parse(player.dataset.streamSources || '[]');
        } catch (error) {
            return [];
        }
    })();

    let lastSent = 0;

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

    const sendProgress = (completed = false) => {
        const current = Math.floor(player.currentTime || 0);
        if (!completed && current - lastSent < 15) {
            return;
        }

        const progress = player.duration ? Math.floor((current / player.duration) * 100) : 0;
        lastSent = current;

        fetch('/watch/progress', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
            },
            body: JSON.stringify({
                watchable_type: watchableType,
                watchable_id: watchableId,
                last_position_seconds: current,
                progress_percent: progress,
                completed,
            }),
        }).catch(() => {});
    };

    player.addEventListener('timeupdate', () => sendProgress(false));
    player.addEventListener('ended', () => sendProgress(true));
}
