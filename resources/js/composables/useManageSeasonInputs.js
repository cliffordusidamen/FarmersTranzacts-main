import { ref } from "vue";

export default function useManageSeasonInputs(farmSeason) {

    const seasonInputs = ref(farmSeason?.season_inputs || []);
    const editSeasonInput = ref({});
    const editInputIndex = ref(null)
    const seasonInputModalId = 'seasonInputModal';
    const seasonInputModelEventName = 'show-season-input-form';
    const showSeasonInputsModal = () => {
        const seasonInputModalEvent = new CustomEvent(seasonInputModelEventName, {
            detail: {},
        });
        document.dispatchEvent(seasonInputModalEvent);
    }

    const resetEditInput = () => {
        editSeasonInput.value = {}
        editInputIndex.value = null
    }
    const handleSeasonInputSubmission = (seasonInputData) => {
        if (editInputIndex.value === null) {
            seasonInputs.value.push({...seasonInputData})
        } else {
            seasonInputs.value[editInputIndex.value] = {...seasonInputData}
        }
        $(`#${seasonInputModalId}`).modal('hide')
        resetEditInput()
    }

    const removeInput = (inputIndex) => {
        if (seasonInputs.value.length <= 1) {
            seasonInputs.value = []
            return;
        }

        const newSeasonInputs = seasonInputs.value.filter((_input, _index) => inputIndex != _index)
        seasonInputs.value = [...newSeasonInputs];
    }
    const setEditInput = (inputIndex) => {
        if (undefined === seasonInputs.value[inputIndex]) {
            return;
        }

        editSeasonInput.value = seasonInputs.value[inputIndex]
        editInputIndex.value = inputIndex

        showSeasonInputsModal()
    }


    return {
        seasonInputs,
        editSeasonInput,
        editInputIndex,
        seasonInputModalId,
        seasonInputModelEventName,
        showSeasonInputsModal,
        resetEditInput,
        handleSeasonInputSubmission,
        removeInput,
        setEditInput,
    }
}