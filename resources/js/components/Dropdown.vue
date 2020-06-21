<template>
    <div class="dropdown relative">
        <div @click="isOpen = !isOpen">
            <slot name="trigger"></slot>
        </div>

        <div
            v-show="isOpen"
            class="absolute bg-card rounded py-2 dropdown-menu w-full"
            :class="align == 'left' ? 'pin-l' : 'pin-r'"
            :style="{ width }"
        >
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        width: { default: "auto" },
        align: { default: "left" }
    },

    data() {
        return { isOpen: false };
    },

    watch: {
        isOpen(isOpen) {
            if (isOpen) {
                document.addEventListener("click", this.closeIfClickOutside);
            }
        }
    },

    methods: {
        closeIfClickOutside(event) {
            if (!event.target.closest(".dropdown")) {
                
                this.isOpen = false;

                document.addEventListener("click", this.closeIfClickOutside);
            }
        }
    }
};
</script>
