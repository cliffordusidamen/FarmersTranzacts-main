<script setup>
import useManageSeasonInputs from '@/composables/useManageSeasonInputs';
import DefaultModal from "@/Components/DefaultModal";
import SeasonInputForm from "@/Components/Forms/SeasonInputForm"

const props = defineProps({
    farmSeason: {
        type: Object,
        default: (() => ({})),
    },
});

const emits = defineEmits([
    'seasonInputsChanged',
]);

const {
    seasonInputs,
    editSeasonInput,
    editInputIndex,
    seasonInputModalId,
    seasonInputModelEventName,
    showSeasonInputsModal,
    resetEditInput,
    handleSeasonInputSubmission,
    showSeasonInputError,
    removeInput,
    setEditInput,
} = useManageSeasonInputs(props.farmSeason);

const onSeasonInputSubmission = (seasonInputData) => {
    handleSeasonInputSubmission(seasonInputData);
    emits('seasonInputsChanged', seasonInputs.value);
};
</script>

<template>
    <div>
        <div class="bg-light rounded border p-3 my-4">
            <div
                class="alert alert-warning"
                v-show="showSeasonInputError && !seasonInputs.length"
            >
                Add at least one(1) season input.
            </div>

            <h5 class="text-center text-success border-bottom text-uppercase">
                Season inputs
            </h5>
            <div class="mx-4 text-center text-muted mt-n4 mb-1">
                All items/materials used in the season
            </div>

            <div v-if="!seasonInputs.length" class="px-2 py-2 text-center">
                Add at least one input for the season<br />
            </div>

            <div v-else>
                <div
                    v-for="(seasonInput, inputIndex) in seasonInputs"
                    :key="inputIndex"
                    class="d-flex align-items-center justify-content-start border-top"
                >
                    <div class="flex-grow-1">
                        <strong>{{ `${seasonInput.name} - ${seasonInput.quantity} ${seasonInput.unit}` }}</strong>
                        <br />
                        &#8358;{{ `${Number(seasonInput.cost_per_unit).toLocaleString()} per ${seasonInput.unit}` }}
                    </div>
                    <button
                        type="button"
                        class="btn btn-dark min-width-0"
                        @click="() => setEditInput(inputIndex)"
                    >
                        <i class="icon-edit"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger min-width-0"
                        @click="() => removeInput(inputIndex)"
                    >
                        <i class="icon-close"></i>
                    </button>
                </div>
            </div>

            <div class="mt-2">
                <button
                    type="button"
                    class="btn btn-block btn-dark text-uppercase"
                    @click="showSeasonInputsModal"
                >
                    <i class="icon-plus"></i>
                    Add input
                </button>
            </div>
        </div>

        <default-modal
            :id="seasonInputModalId"
            :show-event="seasonInputModelEventName"
        >
            <div class="text-right">
                <button type="button" class="btn btn-danger p-0 text-uppercase min-width-0" data-dismiss="modal">
                    <i class="icon-close"></i>
                </button>
            </div>
            <div class="p-3">

                <season-input-form
                    :key="editSeasonInput"
                    :season-input="editSeasonInput"
                    :index="editInputIndex"
                    @on-submit="onSeasonInputSubmission"
                />
            </div>
        </default-modal>
    </div>
</template>