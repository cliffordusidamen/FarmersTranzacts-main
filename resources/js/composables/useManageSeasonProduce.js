import { ref } from "vue";

export default function useManageSeasonProduces(farmSeason) {

    const seasonProduces = ref(farmSeason?.season_produces || []);
    const editSeasonProduce = ref({});
    const editProduceIndex = ref(null)
    const seasonProduceModalId = 'seasonProduceModal';
    const seasonProduceModelEventName = 'show-season-produce-form';
    const showSeasonProducesModal = () => {
        const seasonProduceModalEvent = new CustomEvent(seasonProduceModelEventName, {
            detail: {},
        });
        document.dispatchEvent(seasonProduceModalEvent);
    }

    const resetEditProduce = () => {
        editSeasonProduce.value = {}
        editProduceIndex.value = null
    }
    const handleSeasonProduceSubmission = (seasonProduceData) => {
        if (editProduceIndex.value === null) {
            seasonProduces.value.push({...seasonProduceData})
        } else {
            seasonProduces.value[editProduceIndex.value] = {...seasonProduceData}
        }
        $(`#${seasonProduceModalId}`).modal('hide')
        resetEditProduce()
    }

    const removeProduce = (produceIndex) => {
        if (seasonProduces.value.length <= 1) {
            seasonProduces.value = []
            return;
        }

        const newSeasonProduces = seasonProduces.value.filter((_produce, _index) => produceIndex != _index)
        seasonProduces.value = [...newSeasonProduces];
    }
    const setEditProduce = (produceIndex) => {
        if (undefined === seasonProduces.value[produceIndex]) {
            return;
        }

        editSeasonProduce.value = seasonProduces.value[produceIndex]
        editProduceIndex.value = produceIndex

        showSeasonProducesModal()
    }


    return {
        seasonProduces,
        editSeasonProduce,
        editProduceIndex,
        seasonProduceModalId,
        seasonProduceModelEventName,
        showSeasonProducesModal,
        resetEditProduce,
        handleSeasonProduceSubmission,
        removeProduce,
        setEditProduce,
    }
}