<script setup>
import useManageSeasonProduce from '@/composables/useManageSeasonProduce';
import DefaultModal from "@/Components/DefaultModal";
import SeasonProduceForm from "@/Components/Forms/SeasonProduceForm"

const props = defineProps({
    farmSeason: {
        type: Object,
        default: (() => ({})),
    },
});

const emits = defineEmits([
    'seasonProducesChanged',
]);

const {
    seasonProduces,
    editSeasonProduce,
    editProduceIndex,
    seasonProduceModalId,
    seasonProduceModelEventName,
    showSeasonProducesModal,
    resetEditProduce,
    handleSeasonProduceSubmission,
    showSeasonProduceError,
    removeProduce,
    setEditProduce,
} = useManageSeasonProduce(props.farmSeason);

const onSeasonProduceSubmission = (seasonProduceData) => {
    handleSeasonProduceSubmission(seasonProduceData);
    emits('seasonProducesChanged', seasonProduces.value);
};
</script>

<template>
    <div>
        <div class="bg-light rounded border p-3 my-4">
            <div
                class="alert alert-warning"
                v-show="showSeasonProduceError && !seasonProduces.length"
            >
                Add at least one(1) season produce.
            </div>

            <h5 class="text-center text-success border-bottom text-uppercase">
                Season produce
            </h5>
            <div class="mx-4 text-center text-muted mt-n4 mb-1">
                All items/materials used in the season
            </div>

            <div v-if="!seasonProduces.length" class="px-2 py-2 text-center">
                Add at least one product for the season<br />
            </div>

            <div v-else>
                <div
                    v-for="(seasonProduce, produceIndex) in seasonProduces"
                    :key="produceIndex"
                    class="d-flex align-items-center justify-content-start border-top"
                >
                    <div class="flex-grow-1">
                        <strong>{{ `${seasonProduce.name} - ${seasonProduce.quantity_produced} ${seasonProduce.unit}` }}</strong>
                        <br />
                        Sold
                        {{ `${seasonProduce.quantity_produced} ${seasonProduce.unit}` }}
                        at
                        &#8358;{{ `${Number(seasonProduce.price_per_unit).toLocaleString()} per ${seasonProduce.unit}` }}
                    </div>
                    <button
                        type="button"
                        class="btn btn-dark min-width-0"
                        @click="() => setEditProduce(produceIndex)"
                    >
                        <i class="icon-edit"></i>
                    </button>
                    <button
                        type="button"
                        class="btn btn-danger min-width-0"
                        @click="() => removeProduce(produceIndex)"
                    >
                        <i class="icon-close"></i>
                    </button>
                </div>
            </div>

            <div class="mt-2">
                <button
                    type="button"
                    class="btn btn-block btn-dark text-uppercase"
                    @click="showSeasonProducesModal"
                >
                    <i class="icon-plus"></i>
                    Add produce
                </button>
            </div>
        </div>

        <default-modal
            :id="seasonProduceModalId"
            :show-event="seasonProduceModelEventName"
        >
            <div class="text-right">
                <button type="button" class="btn btn-danger p-0 text-uppercase min-width-0" data-dismiss="modal">
                    <i class="icon-close"></i>
                </button>
            </div>
            <div class="p-3">

                <season-produce-form
                    :key="editSeasonProduce"
                    :season-produce="editSeasonProduce"
                    :index="editProduceIndex"
                    @on-submit="onSeasonProduceSubmission"
                />
            </div>
        </default-modal>
    </div>
</template>