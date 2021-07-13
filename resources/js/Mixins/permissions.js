export default {
    data() {
        const vueJs = this
        return  {
            ...vueJs.$page.props.permissions
        }
    }
}