<template>
    <div>
        <InputLabel v-if="label" :for="id" :required="required" :large-label="largeLabel">
            {{ label }}
        </InputLabel>

        <template v-if="imagePreview">
            <div class="aspect-w-3 aspect-h-2 | mt-2 mb-4">
                <img :src="imagePreview" alt="" class="object-cover | rounded-lg" />
            </div>
        </template>

        <div
            :class="wrapperClass"
            @drop.prevent="onFileUpload($event.dataTransfer.files)"
            @dragenter.prevent
            @dragover.prevent
        >
            <div v-if="!uploadedFile" class="space-y-2 text-center">
                <FontAwesomeIcon icon="file-upload" class="text-4xl text-gray-400" />
                <div class="flex flex-col text-sm text-gray-600">
                    <p class="pl-1" v-text="trans('action.file_upload.text')" />

                    <label
                        :for="id"
                        class="relative cursor-pointer bg-white rounded-md font-medium text-primary hover:text-primary-light focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary"
                    >
                        <span class="font-bold" v-text="trans('action.file_upload.clickable')" />

                        <input
                            :id="id"
                            ref="input"
                            v-bind="$attrs"
                            :class="inputClass"
                            type="file"
                            :value="value"
                            :required="required"
                            @change="onFileUpload($event.target.files)"
                        />
                    </label>
                </div>

                <p v-if="requirements" class="text-xs text-gray-500" v-text="requirements" />
            </div>

            <div v-else class="w-full flex flex-col items-center text-sm">
                <div class="w-full flex flex-row items-center">
                    <span class="whitespace-nowrap overflow-hidden text-ellipsis" v-text="uploadedFile.name" />
                    <span
                        class="whitespace-nowrap text-xs text-gray-400 ml-1"
                        v-text="`(${filesize(uploadedFile.size)})`"
                    />
                </div>

                <span
                    class="font-bold text-primary cursor-pointer mt-2"
                    @click="remove"
                    v-text="trans('action.file_upload.remove')"
                />
            </div>
        </div>

        <InvalidFeedback v-if="error" :error="error" class="mt-2" />

        <HelpText :text="text" class="mt-2" />
    </div>
</template>

<script>
import InputLabel from '@/components/form/shared/InputLabel';
import InvalidFeedback from '@/components/form/shared/InvalidFeedback';
import HelpText from '@/components/form/shared/HelpText';

export default {
    components: {
        InputLabel,
        InvalidFeedback,
        HelpText,
    },
    inheritAttrs: false,
    props: {
        id: {
            type: String,

            /**
             * Set a default id for the text input.
             *
             * @returns {string}
             */
            default() {
                // eslint-disable-next-line
                return `file-input-${this._uid}`;
            },
        },
        type: {
            type: String,
            default: 'text',
        },
        value: {
            type: File,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
        error: {
            type: String,
            default: null,
        },
        required: {
            type: Boolean,
            default: false,
        },
        text: {
            type: String,
            default: null,
        },
        requirements: {
            type: String,
            default: '',
        },
        imagePreview: {
            type: String,
            default: null,
        },
        largeLabel: {
            type: Boolean,
            default: false,
        },
    },
    /**
     * Holds the data.
     *
     * @returns {object}
     */
    data() {
        return {
            uploadedFile: null,
        };
    },
    computed: {
        /**
         * Determines the input class based on the current state.
         *
         * @returns {string}
         */
        inputClass() {
            const inputClasses = ['sr-only'];

            if (this.error) {
                inputClasses.push(
                    'border-red-300 text-red-900 focus:outline-none focus:ring-red-500 focus:border-red-500'
                );
            }

            return inputClasses.join(' | ');
        },

        /**
         * Determines the wrapper class based on the current state.
         *
         * @returns {string}
         */
        wrapperClass() {
            const wrapperClasses = ['mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md'];

            if (this.error) {
                wrapperClasses.push('border-red-300');
            } else {
                wrapperClasses.push('border-gray-300');
            }

            return wrapperClasses;
        },
    },
    methods: {
        /**
         * Focuses the input.
         */
        focus() {
            this.$refs.input.focus();
        },

        /**
         * $emits to the parent.
         *
         * Triggers whenever someone uploads a file via the input.
         * Either by using the file browser or dragging and dropping.
         *
         * @param {object} fileList
         */
        onFileUpload(fileList) {
            const uploadedFile = fileList[0];

            this.uploadedFile = uploadedFile;

            this.$emit('change', uploadedFile);
        },

        /**
         * Removes the uploaded file
         */
        remove() {
            this.uploadedFile = null;

            this.$emit('change', null);
        },

        /**
         * Calculates the filesize
         *
         * @param {number} size
         *
         * @returns {string}
         */
        filesize(size) {
            const unitIndex = Math.floor(Math.log(size) / Math.log(1024));

            const unit = ['B', 'kB', 'MB', 'GB', 'TB'][unitIndex];
            const displaySize = (size / 1024 ** unitIndex).toFixed(2);

            return `${displaySize} ${unit}`;
        },
    },
};
</script>
