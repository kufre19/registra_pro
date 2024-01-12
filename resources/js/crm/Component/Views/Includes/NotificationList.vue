<template>
    <div class="content-wrapper">
        <app-breadcrumb
            :directory="$t('notifications')"
            :icon="'bell'"
            :page-title="$t('all_notifications')"
        />

        <!--    <app-table :id="'notification-table'" :options="options"/>-->

        <div class="dropdown-items-wrapper custom-scrollbar">
            <a
                v-for="(item, index) in notifications"
                :key="index"
                :class="[item.status == 'new' ? 'shadow' : '']"
                class="card border-0 mt-3 p-3"
                href="#"
                @click.prevent="readNotification(item.id)"
            >
                <div class="row">
                    <div class="col-9">
                        <div class="media">
                            <app-avatar
                                :img="item.img ? urlGenerator(item.img) : item.img"
                                :title="item.name"
                                avatar-class="avatars-w-60"
                                class="mr-3"
                            />
                            <div class="media-body">
                                <p class="my-0 default-font-color" v-html="item.title"></p>
                                <!-- <span class="text-muted">
                                                  {{ textTruncate(item.description, 25) }}
                                              </span>-->
                                <span class="primary-text-color link">
                  <span class="mr-3">{{ item.date }}</span>
                  <span
                      v-if="item.status == 'new'"
                      class="badge badge-primary badge-pill"
                  >{{ item.status }}</span
                  >
                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
            <span class="primary-text-color time-text float-right">{{
                    item.time
                }}</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "../../../Helpers/helpers";
import {mapGetters} from "vuex";
import {axiosPost} from "../../../Helpers/AxiosHelper";

export default {
    name: "NotificationList",
    computed: {
        ...mapGetters({
            notifications: "getAllNotification",
        }),
    },
    data() {
        return {
            urlGenerator,
        };
    },
    methods: {
        readNotification(id) {
            let checkRole = window.user?.roles?.[0];
            if (checkRole.name === 'Client') {
                window.location = urlGenerator(route('deals.lists'))
            } else {
                axiosPost(route('user-notifications.mark-as-read', {id: id})).then(
                    ({data}) => {
                        if (data.data.url) {
                            window.location = data.data.url;
                        }
                    }
                );
            }
        },
    },
};
</script>
