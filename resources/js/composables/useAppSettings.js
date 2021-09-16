import { usePage } from '@inertiajs/inertia-vue3'

export default function useAppSettings() {
    const { app_settings } = usePage().props.value
    return {
        hotline: app_settings.hotline,
        email: app_settings.email,
        address: app_settings.address,
        social_facebook: app_settings.social_facebook,
        social_instagram: app_settings.social_instagram,
        social_whatsapp: app_settings.social_whatsapp,
        social_linkedin: app_settings.social_linkedin,
        social_youtube: app_settings.social_youtube,
        social_twitter: app_settings.social_twitter,
    }
}