<script setup>
import { useForm, usePage } from "@inertiajs/inertia-vue3"
import CustomSelect from '@/Components/CustomSelect'


const { app_states, queryName, queryState } = usePage().props.value
const form = useForm({
    name: queryName || '',
    state: queryState || '',
})

const submitMainSearch = e => {
    form.get(route('referrals.search'), {
        onFinish: () => {
            form.processing = false;
        },
    })
}
</script>

<template>
    <form @submit.prevent="submitMainSearch">
        <div class="row">
            <div class="col-md-4 mb-2">
                <custom-select
                    id="state"
                    name="state"
                    v-model="form.state"
                    :options="app_states"
                    placeholder="Select one"
                />
            </div>

            <div class="col-md-4 mb-2">
                <input
                    type="search"
                    class="form-control"
                    name="name"
                    id="name"
                    v-model="form.name"
                    placeholder="Search product ..."
                />
            </div>

            <div class="col-md-4 mb-2">
                <button
                    :disabled="form.processing"
                    class="btn btn-success"
                    type="submit"
                >
                    <i class="icon-search mr-1"></i>
                    SEARCH
                </button>
            </div>

        </div>
    </form>
</template>
