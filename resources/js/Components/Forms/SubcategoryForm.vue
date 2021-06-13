<template>
    <form ref="subcategoryForm">
        <div class="form-box">
            <div class="d-flex flex-column flex-md-row lead border-bottom">
                <strong>Category:</strong>
                <span class="flex-fill text-right">{{ category.name }}</span>
            </div>

            <div class="form-group">
                <jet-label class="required" for="name" value="Subcategory Name" />
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
            <div class="form-group text-center">
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
            required: true,
            type: Object,
        },
        subcategory: {
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
                name: vueObj.$page.props.subcategory.name ?? "",
                description: vueObj.$page.props.subcategory.description ?? "",
            }),
        }
    },

    mounted() {
        const vueObj = this;
        this.jqueryValidator = $(this.$refs.subcategoryForm).validate({
            submitHandler() {
                vueObj.$refs.subcategoryForm.classList.add("was-validated");
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
            this.form.post(this.route("categories.save_subcategory", [this.category.id, this.subcategory.id ?? null]), {
                errorBag: 'SaveProductSubcategory',
                preserveScroll: true,
                 onFinish: () => {
                    this.form.processing = false;
                },
            });
        }
    }
}
</script>
