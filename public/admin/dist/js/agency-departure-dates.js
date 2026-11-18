(() => {
    function updateReturnDate(form) {
        const start = form.querySelector('[name="ts_start_date"]');
        const end = form.querySelector('[name="ts_end_date"]');
        if (!start || !end) return;
        if (!start.value) {
            end.value = '';
            return;
        }
        const date = new Date(start.value + 'T00:00:00Z');
        if (Number.isNaN(date.getTime())) return;
        // The departure day is day one, matching the tour booking calculation.
        const days = Math.max(1, Number(form.dataset.durationDays) || 1);
        date.setUTCDate(date.getUTCDate() + days - 1);
        end.value = date.toISOString().slice(0, 10);
    }

    document.addEventListener('change', (event) => {
        if (!event.target.matches('[name="ts_start_date"]')) return;
        const form = event.target.closest('form[data-agency-departure]');
        if (form) updateReturnDate(form);
    });

    document.querySelectorAll('form[data-agency-departure]').forEach((form) => {
        const end = form.querySelector('[name="ts_end_date"]');
        if (end && !end.value) updateReturnDate(form);
    });
})();
