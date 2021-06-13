<template>
    <form ref="categoryForm">
        <div class="form-box">
            <div class="form-group">
                <jet-label class="required" for="name" value="Category Name" />
                <jet-input
                    name="name"
                    maxlength="255"
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />
                <jet-input-error :message="form.errors.name" />
            </div>
            <div class="form-group">
                <jet-label for="description" value="Description" />
                <jet-textarea
                    rows="3"
                    name="description"
                    maxlength="255"
                    id="description"
                    type="text"
                    v-model="form.description"
                    autocomplete="description"
                />
                <jet-input-error :message="form.errors.description" />
            </div>
            <div class="form-group">
                <jet-button :disabled="form.processing">SUBMIT</jet-button>
            </div>
        </div>
    </form>
</template>

<script>
import JetLabel from '@/Jetstream/Label'
import JetInput from '@/Jetstream/Input'
import JetTextarea from '@/Jetstream/Textarea'
import JetButton from '@/Jetstream/Button'
import JetInputError from '@/Jetstream/InputError'

export default {

    props: {
        category: {
            type: Object,
            default: () => ({}),
        },
    },

    components: {
        JetInput,
        JetLabel,
        JetTextarea,
        JetButton,
        JetInputError,
    },

    data() {
        const vueObj = this;

        return {
            jqueryValidator: null,
            form: this.$inertia.form({
                _method: "POST",
                name: vueObj.category.name ?? "",
                description: vueObj.category.description ?? "",
            }),
        }
    },

    mounted() {
        const vueObj = this;
        this.jqueryValidator = $(this.$refs.categoryForm).validate({
            submitHandler() {
                vueObj.$refs.categoryForm.classList.add("was-validated");
                vueObj.submit();
            },
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                    maxlength: 255,
                },
                description: {
                    minlength: 3,
                    maxlength: 255,
                },
            },
        });
    },

    methods: {
        submit() {
            this.form.processing = true;

            const actionRoute = undefined == this.category.id
                ? this.route("categories.save")
                : this.route("categories.save", this.category.id);

            this.form.post(actionRoute, {
                errorBag: 'SaveProductCategory',
                preserveScroll: true,
                 onFinish: () => {
                    this.form.processing = false;
                },
            });
        }
    }
}
</script>
