<template>
 <modal name="new-project" classes="p-10 bg-white rounded-lg" height="auto">
        <h1 class="font-normal mb-16 text-center text-2xl">Let's start something new</h1>
        
        <form @submit.prevent="submit">
            <div class="flex">
                <div class="flex-1 mr-4">
                    <div class="mb-4">
                        <label for="title" class="text-sm block mb-2">Title</label>
                        <input type="text" id="title" 
                            class="border p-2 text-xs block w-full rounded"
                            :class="errors.title ? 'border-red-600' : 'border-muted-light'"
                            v-model="form.title">
                        <span class="text-xs italic text-red-600" v-if="errors.title" v-text="errors.title[0]"></span>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="text-sm block mb-2">Description</label>
                        <textarea id="description" 
                            class="border p-2 text-xs block w-full rounded" 
                            :class="errors.title ? 'border-red-600' : 'border-muted-light'"
                            rows="7"
                            v-model="form.description"></textarea>
                        <span class="text-xs italic text-red-600" v-if="errors.description" v-text="errors.description[0]"></span>
                    </div>
                </div>

                <div class="flex-1  ml-4 text-xs">
                    <div class="mb-4">
                        <label for="title" class="text-sm block mb-2">Need some tasks?</label>
                        <input type="text" 
                            class="border border-muted-light mb-2 p-2 text-xs block w-full rounded" 
                            placeholder="Task 1" 
                            v-for="task in form.tasks"
                            v-model="task.value">
                    </div>
                    <button class="inline-flex items-center" @click="addTask">
                        <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 18.001 18.001;" xml:space="preserve" width="18px" height="18px"><g><g>
                            <g>
                                <path d="M437.02,74.981c-48.352-48.352-112.638-74.98-181.018-74.98S123.334,26.63,74.982,74.981    C26.63,123.333,0,187.621,0,256.001C0,324.38,26.629,388.667,74.981,437.02C123.334,485.372,187.622,512,256.002,512    c68.379,0,132.666-26.628,181.018-74.98c48.352-48.352,74.981-112.639,74.981-181.019C512,187.621,485.372,123.333,437.02,74.981z     M256.002,482C131.384,482,30,380.617,30,256.001c0.001-124.616,101.385-226,226.002-226c124.615,0,225.998,101.383,225.999,226    C482,380.617,380.617,482,256.002,482z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#96A8B6"/>
                            </g>
                            </g><g>
                            <g>
                                <path d="M400.6,241.001H271l0.001-129.601c0-8.284-6.716-15-15-15s-15,6.716-15,15L241,241.001H111.401c-8.284,0-15,6.716-15,15    s6.716,15,15,15H241v129.6c0,8.284,6.716,15,15,15s15-6.716,15-15v-129.6h129.6c8.284,0,15-6.716,15-15    S408.884,241.001,400.6,241.001z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#96A8B6"/>
                            </g>
                            </g></g> 
                        </svg>

                        <span>Add New Task Field</span>
                    </button>
                </div>
            </div>

            <footer class="flex justify-end">
                <button class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-2 rounded-lg text-xs mr-4" 
                        @click="$modal.hide('new-project')">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-400 hover:bg-blue-500 text-white font-bold py-2 px-2 rounded-lg text-xs">Create Project</button>
            </footer>
        </form>
    </modal>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    title:'',
                    description:'',
                    tasks: [
                        {value: ''},
                    ]
                },

                errors: {}
            };
        },


        methods: {
            addTask() {
                this.form.tasks.push({ value : ''});
            },

            submit() {
                axios.post('/projects', this.form)
                     .then(response => {
                         location = response.data.message;
                     })
                     .catch(error => {
                         this.errors = error.response.data.errors;
                     });
            }

        } 
    }
</script>