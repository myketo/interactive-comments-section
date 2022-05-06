export default {
    methods: {
        mixinTextareaAutoResize(event) {
            if (!event.target.value) return

            event.target.style.height = "auto";
            event.target.style.height = `${event.target.scrollHeight}px`;

            this.$emit('update:modelValue', event.target.value)
        },
    },
};
