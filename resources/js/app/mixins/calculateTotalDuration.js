export default {
    methods: {
        calculateTotalDuration(startTime, endTime) {
            const start = startTime.split(":");
            const end = endTime.split(":");

            const startDate = new Date(0, 0, 0, start[0], start[1], 0);
            const endDate = new Date(
                0,
                0,
                parseInt(end[0]) < parseInt(start[0]) ? 1 : 0,
                end[0],
                end[1],
                0
            );
            let diff = endDate.getTime() - startDate.getTime();
            let hours = Math.floor(diff / 1000 / 60 / 60);
            diff -= hours * 1000 * 60 * 60;
            const minutes = Math.floor(diff / 1000 / 60);
            if (hours == -1) {
                hours = 23;
            }
            return `${hours}.${minutes}`;
        }
    }
};
