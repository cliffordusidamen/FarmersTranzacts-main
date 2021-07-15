<template>
    <div class="alert alert-danger text-sm p-2" role="alert" v-if="hasErrors">
        <div class="font-weight-bold d-none">Whoops! Something went wrong.</div>

        <ul class="list-unstyled">
            <li v-for="(error, key) in errors" :key="key" v-html="getErrorMessages(error)"></li>
        </ul>
    </div>
</template>

<script>
    export default {
        computed: {
            errors() {
                return this.$page.props.errors
            },
            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        },

        methods: {
            getErrorMessages(error) {
                if (error.constructor === String) {
                    return error;
                }
                let messages = [];
                let errorKeys = Object.keys(error);

                if (errorKeys.length == 0) {
                    // console.error('Validation errors format not recognized', error);
                    return '';
                }

                errorKeys.forEach(function(val, index) {
                    messages.push(error[val]);
                });
                
                return '<span class="badge bg-secondary me-1"></span>'
                    + messages.join('<br /><span class="badge bg-secondary me-1"></span>');
            },
        },
    }
</script>