export default function useShortenNumber() {
    const shortenNumber = (numericValue, decimalPlaces = 1) => {
        const billion = 1_000_000_000;
        const million = 1_000_000;
        const numberValue = Number(numericValue);
        if (isNaN(numberValue)) {
            numericValue;
        }

        if (numberValue >= billion) {
            return Number(numberValue / billion).toFixed(decimalPlaces) + "B";
        }

        if (numberValue >= million) {
            return Number(numberValue / million).toFixed(decimalPlaces) + "M";
        }

        if (numberValue >= 1000) {
            return Number(numberValue / 1000).toFixed(decimalPlaces) + "k";
        }

        return numberValue;
    };

    return {
        shortenNumber,
    };
}
