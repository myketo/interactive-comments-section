export default {
    data() {
        return {
            isMobile: false,
        }
    },

    methods: {
        setIsMobile() {
            this.isMobile = window.innerWidth < 768
        }
    },

    mounted() {
        this.setIsMobile()

        window.addEventListener('resize', this.setIsMobile, { passive: true })
    },
}