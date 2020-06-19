<template>
    <modal name="new-project" classes="p-10 bg-card rounded-lg" height="auto">
        <h1 class="font-normal mb-16 text-center text-2xl">
            Let's start something new.
        </h1>
        <form @submit.prevent="submit">
            <div class="flex">
                <div class="flex-1 mr-4">
                    <div class="mb-4">
                        <label for="title" class="text-sm block mb-2"
                            >Title</label
                        >
                        <input
                            type="text"
                            id="title"
                            v-model="form.title"
                            :class="
                                errors.title ? 'border-error' : 'border-muted'
                            "
                            placeholder="My awesome nexr project"
                            class="border rounded py-2 px-2 text-xs block w-full "
                        />
                        <p
                            class="text-xs text-italic text-error"
                            v-if="errors.title"
                            v-text="errors.title[0]"
                        ></p>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="text-sm block mb-2"
                            >Description</label
                        >
                        <textarea
                            v-model="form.description"
                            :class="
                                errors.description
                                    ? 'border-error'
                                    : 'border-muted'
                            "
                            class="w-full rounded border border-gray text-xs block py-2 px-2"
                            placeholder="I should start learning piano."
                            id="description"
                            rows="7"
                        ></textarea>
                        <p
                            class="text-xs text-italic text-error"
                            v-if="errors.description"
                            v-text="errors.description[0]"
                        ></p>
                    </div>
                </div>
                <div class="flex-1 ml-4">
                    <div class="mb-4">
                        <label for="task" class="text-sm block mb-2"
                            >Need some tasks?</label
                        >
                        <input
                            type="text"
                            id="task"
                            placeholder="task 1"
                            class="border border-gray rounded py-2 px-2 text-xs block w-full mb-2"
                            v-for="task in form.tasks"
                            v-model="task.body"
                            :key="task.id"
                        />
                    </div>
                    <div class="mb-4">
                        <button
                            type="button"
                            class="inline-flex items-center"
                            @click="addTask"
                        >
                            <svg
                                style="height: 20px; stroke: #000; opacity: .5;"
                                viewBox="0 0 100 100"
                            >
                                <circle
                                    cx="50"
                                    cy="50"
                                    r="45"
                                    fill="none"
                                    stroke-width="7.5"
                                ></circle>
                                <line
                                    x1="32.5"
                                    y1="50"
                                    x2="67.5"
                                    y2="50"
                                    stroke-width="5"
                                ></line>
                                <line
                                    x1="50"
                                    y1="32.5"
                                    x2="50"
                                    y2="67.5"
                                    stroke-width="5"
                                ></line>
                            </svg>
                            <span class="ml-2 text-xs">Add new task field</span>
                        </button>
                    </div>
                </div>
            </div>
            <footer class="mt-4 flex justify-end">
                <button
                    type="button"
                    class="btn mr-2 is-outlined"
                    @click="$modal.hide('new-project')"
                >
                    Cancel
                </button>
                <button type="submit" class="btn">Create Project</button>
            </footer>
        </form>
    </modal>
</template>

<script>
export default {
    data() {
        return {
            form: {
                title: "",
                description: "",
                tasks: [{ body: "" }]
            },
            errors: {}
        };
    },

    methods: {
        addTask() {
            this.form.tasks.push({ body: "" });
        },

        async submit() {
            try {
                // let response = await axios.post('/projects', this.form);
                // location = response.data.redirect_url;

                location = await axios.post("/projects", this.form);
            } catch (error) {
                this.errors = error.response.data.errors;
            }
        }
    }
};
</script>
