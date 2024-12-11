<template>
    <div>
        <div
            class="rounded border"
            :class="hasError ? 'border-red' : 'border-gray'"
        >
            <div
                v-if="editor"
                class="flex flex-col items-center bg-gray-50 text-gray-darker | py-2 border-b"
            >
                <div class="w-full | flex flex-row items-center | divide-x divide-gray | mb-1">
                    <div class="px-2">
                        <button
                            type="button"
                            class="rounded-md hover:bg-gray focus:outline-none | px-2 | group"
                            :class="{ 'bg-gray text-black': editor.isActive('bold') }"
                            @click="editor.chain().focus().toggleBold().run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="bold"
                            />
                        </button>

                        <button
                            type="button"
                            class="rounded-md hover:bg-gray | px-2 | group"
                            :class="{ 'bg-gray text-black': editor.isActive('italic') }"
                            @click="editor.chain().focus().toggleItalic().run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="italic"
                            />
                        </button>

                        <button
                            type="button"
                            class="rounded-md hover:bg-gray | px-2 | group"
                            :class="{ 'bg-gray text-black': editor.isActive('underline') }"
                            @click="editor.chain().focus().toggleUnderline().run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="underline"
                            />
                        </button>
                    </div>

                    <div class="px-4">
                        <button
                            type="button"
                            class="rounded-md hover:bg-gray | px-2 | group"
                            :class="{ 'bg-gray text-black': editor.isActive('bulletList') }"
                            @click="editor.chain().focus().toggleBulletList().run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="list-ul"
                            />
                        </button>

                        <button
                            type="button"
                            class="rounded-md hover:bg-gray | px-2 | group"
                            :class="{ 'bg-gray text-black': editor.isActive('orderedList') }"
                            @click="editor.chain().focus().toggleOrderedList().run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="list-ol"
                            />
                        </button>
                    </div>

                    <div class="px-4">
                        <button
                            type="button"
                            class="rounded-md hover:bg-gray | px-2 | group"
                            :class="{ 'bg-gray text-black': editor.isActive('heading', { level: 5 }) }"
                            @click="editor.chain().focus().toggleHeading({ level: 5 }).run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="heading"
                            />
                        </button>

                        <button
                            type="button"
                            class="rounded-md hover:bg-gray focus:bg-gray focus:text-black | px-2 | group"
                            @click="openUrlModal"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="link"
                            />
                        </button>

                        <button
                            type="button"
                            class="rounded-md hover:bg-gray | px-2 | group"
                            @click="editor.chain().focus().unsetLink().run()"
                        >
                            <FontAwesomeIcon
                                class="group-hover:text-black"
                                icon="unlink"
                            />
                        </button>
                    </div>
                </div>
            </div>

            <EditorContent
                :editor="editor"
                class="py-4 bg-white"
            />
        </div>

        <UrlModal
            :open="openModal"
            :previous-url="previousUrl"
            @url="setLink"
            @closed="closeUrlModal"
        />
    </div>
</template>

<script>
import { Editor, EditorContent } from '@tiptap/vue-2';
import StarterKit from '@tiptap/starter-kit';
import Underline from '@tiptap/extension-underline';
import Link from '@tiptap/extension-link';
import UrlModal from '@/components/modal/UrlModal';

export default {
    components: {
        EditorContent,
        UrlModal,
    },
    props: {
        value: {
            type: String,
            default: '',
        },
        hasError: {
            type: Boolean,
            default: false,
        },
    },

    /** @returns {object} */
    data() {
        return {
            editor: null,
            openModal: false,
            previousUrl: null,
        };
    },
    watch: {
        /**
         * Watch for changes to the component value.
         *
         * @param {string} newValue
         */
        value(newValue) {
            const isSame = this.editor.getHTML() === newValue;

            if (isSame) {
                return;
            }

            this.editor.commands.setContent(newValue, false);
        },
    },
    /**
     * Handles the mounted lifecycle event.
     */
    mounted() {
        this.editor = new Editor({
            content: this.value,
            editorProps: {
                attributes: {
                    class: 'short-editor',
                },
            },
            extensions: [StarterKit, Underline, Link],
            onUpdate: () => {
                this.$emit('input', this.editor.getHTML());
            },
        });
    },
    /**
     * Cleanup when unmounting.
     */
    beforeUnmount() {
        this.editor.destroy();
    },
    methods: {
        /**
         * Open the Url input model and set the existing link URL
         */
        openUrlModal() {
            this.previousUrl = this.editor.getAttributes('link').href;
            this.openModal = true;
        },
        /**
         *
         */
        closeUrlModal() {
            this.openModal = false;
        },
        /**
         * @param {string} url
         */
        setLink(url) {
            this.editor.chain().focus().extendMarkRange('link').setLink({ href: url }).run();
        },
    },
};
</script>
