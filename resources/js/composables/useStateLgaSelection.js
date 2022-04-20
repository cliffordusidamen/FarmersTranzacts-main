import { ref } from "vue";

export default function useStateLgaSelection(stateLgas, setSelectedLgaCallback)
{
    const selectedStateLgas = ref({});
    const updateSelectedStateLgas = (state_id, lga_id) => {
        const newSelectedStateLgas = {};

        if (
            undefined === state_id
            || !Number(state_id)
            || undefined == stateLgas[state_id]
        ) {
            selectedStateLgas.value = {};
            return;
        }

        Object.keys(stateLgas[state_id]).forEach((lga_id) => {
            newSelectedStateLgas[lga_id] = stateLgas[state_id][lga_id];
        });

        selectedStateLgas.value = {
            ...newSelectedStateLgas,
        }

        if (
            undefined != setSelectedLgaCallback
            && setSelectedLgaCallback.constructor === Function
        ) {
            return setSelectedLgaCallback(lga_id);
        }

    }

    const handleStateChange = (event) => {
        updateSelectedStateLgas(event.target.value);
    }

    return {
        selectedStateLgas,
        updateSelectedStateLgas,
        handleStateChange,
    };
}