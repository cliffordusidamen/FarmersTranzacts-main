export default {
    computed: {
        isAuthenticated() {
            return this.$page.props.user !== null;
        },
    },

    methods: {
        truncatedUserFullName() {
            return !this.isAuthenticated
                ? ''
                : `${this.$page.props.user.first_name} ${this.$page.props.user.last_name}`;
        },
        truncatedUserName() {
            return this.$page.props.user.first_name.length <= 7
                ? this.$page.props.user.first_name
                : this.$page.props.user.first_name.substr(0, 5) + '...'
        },
        productCategoriesList(vendorProductCategories) {
            let list = [];

            for(const propKey in vendorProductCategories) {
                list.push(vendorProductCategories[propKey].name);
            }
            return list.join(', ');
        },
    },

}