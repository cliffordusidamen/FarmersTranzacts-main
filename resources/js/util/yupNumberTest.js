export default function yupNumberTest(message) {
    return {
        name: 'yupNumberTest',
        test: value => !!Number(value),
        message: message ?? 'Enter a numeric value',
        exclusive: false,
    }
}