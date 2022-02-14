export const getReferralApprovalCssClass = status_number => {
    switch (Number(status_number)) {
        case 1:
            return 'bg-success text-white'
        case -1:
            return 'bg-danger text-white'
        default:
            return 'bg-light text-dark'
    }
}