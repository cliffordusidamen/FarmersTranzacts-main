<template>
    <div class="w-full border-bottom border-gray-300 d-flex flex-column flex-md-row align-items-start">
        <div class="py-3 font-weight-bold col-md-4">
            {{ item.minimum_quantity }} - {{ item.maximum_quantity }}
            <span v-if="productPackage.length">{{ productPackage }}</span>
        </div>

        <div class="flex-grow-1">
            <div
                v-if="item.north_central_cost && item.north_central_delivery_days"
                class="border-bottom border-gray-300 px-3 py-3 d-flex align-items-center justify-content-between"
            >
                <span>North-central</span>

                <span class="text-right">
                    &#8358;{{ Number(item.north_central_cost).toLocaleString() }}
                    ({{ `${Number(item.north_central_delivery_days).toLocaleString()} days` }})

                    <div
                        v-if="Number(item.north_central_pay_on_delivery)"
                        class="d-block text-primary small text-right"
                    >
                        <span class="la la-check"></span>
                        Accept cash on delivery
                    </div>
                </span>
            </div>

            <div
                v-if="item.north_east_cost && item.north_east_delivery_days"
                class="border-bottom border-gray-300 px-3 pb-3 d-flex justify-content-between"
            >
                <span>North-east</span>

                <span class="text-right">
                    &#8358;{{ Number(item.north_east_cost).toLocaleString() }}
                    ({{ `${Number(item.north_east_delivery_days).toLocaleString()} days` }})

                    <div
                        v-if="Number(item.north_east_pay_on_delivery)"
                        class="d-block text-primary small text-right"
                    >
                        <span class="la la-check"></span>
                        Accept cash on delivery
                    </div>
                </span>
            </div>

            <div
                v-if="item.north_west_cost && item.north_west_delivery_days"
                class="border-bottom border-gray-300 px-3 pb-3 d-flex align-items-center justify-content-between"
            >
                <span>North-west</span>

                
                <span class="text-right">
                    &#8358;{{ Number(item.north_west_cost).toLocaleString() }}
                    ({{ `${Number(item.north_west_delivery_days).toLocaleString()} days` }})

                    <div
                        v-if="Number(item.north_west_pay_on_delivery)"
                        class="d-block text-primary small text-right"
                    >
                        <span class="la la-check"></span>
                        Accept cash on delivery
                    </div>
                </span>
            </div>

            <div
                v-if="item.south_east_cost && item.south_east_delivery_days"
                class="border-bottom border-gray-300 px-3 pb-3 d-flex align-items-center justify-content-between"
            >
                <span>south-east</span>

                <span class="text-right">
                    &#8358;{{ Number(item.south_east_cost).toLocaleString() }}
                    ({{ `${Number(item.south_east_delivery_days).toLocaleString()} days` }})

                    <div
                        v-if="Number(item.south_east_pay_on_delivery)"
                        class="d-block text-primary small text-right"
                    >
                        <span class="la la-check"></span>
                        Accept cash on delivery
                    </div>
                </span>
            </div>

            <div
                v-if="item.south_west_cost && item.south_west_delivery_days"
                class="border-bottom border-gray-300 px-3 pb-3 d-flex align-items-center justify-content-between"
            >
                <span>south-west</span>

                <span class="text-right">
                    &#8358;{{ Number(item.south_west_cost).toLocaleString() }}
                    ({{ `${Number(item.south_west_delivery_days).toLocaleString()} days` }})

                    <div
                        v-if="Number(item.south_west_pay_on_delivery)"
                        class="d-block text-primary small text-right"
                    >
                        <span class="la la-check"></span>
                        Accept cash on delivery
                    </div>
                </span>
            </div>

            
                <span class="text-right">
                    &#8358;{{ Number(item.south_south_cost).toLocaleString() }}
                    ({{ `${Number(item.south_south_delivery_days).toLocaleString()} days` }})

                    <div
                        v-if="Number(item.south_south_pay_on_delivery)"
                        class="d-block text-primary small text-right"
                    >
                        <span class="la la-check"></span>
                        Accept cash on delivery
                    </div>
                </span>
        </div>
    </div>

</template>

<script>
export default {

    props: {


        productPackage: {
            type: String,
            default: '',
        },

        item: {
            type: Object,
            required: true,
        }
    },
}
</script>
