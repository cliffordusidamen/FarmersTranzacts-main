import moment from "moment"

export default function useDate() {
    const formatDate = (dateString, formatString) => {
        const dateFormat = undefined == formatString ? 'DD MMM, YYYY' : formatString
        const dateMoment = moment(dateString, true);

        return dateMoment.isValid() ? dateMoment.format(dateFormat) : dateString
    }

    return {
        formatDate,
    }
}