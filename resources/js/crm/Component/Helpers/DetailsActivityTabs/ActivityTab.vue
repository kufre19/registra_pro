<template>
    <form class="mb-0" ref="form" :data-url="props.activitySyncUrl">
        <div class="mb-primary">
            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center">
                        {{ $t("activity") }}
                    </label>
                    <div class="col-sm-10">
                        <app-input
                            type="radio-buttons"
                            :list="activityTypeList"
                            v-model="activity_type_id"
                        />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center">
                        {{ $t("title") }}
                    </label>
                    <div class="col-sm-10">
                        <app-input
                            type="text"
                            :placeholder="$t('enter_title')"
                            :required="true"
                            v-model="formData.title"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex mt-2">{{ $t("description") }}</label>
                    <div class="col-sm-10">
                        <app-input
                            type="textarea"
                            :text-area-rows="5"
                            :placeholder="$t('description_here')"
                            v-model="formData.description"
                        />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center">
                        {{ $t("set_schedule") }}
                    </label>
                    <div class="col-sm-10">
                        <div class="row mb-1">
                            <div class="col-lg-7 pr-lg-0">
                                <app-input
                                    type="date"
                                    :placeholder="$t('start_date')"
                                    v-model="formData.started_at"
                                    @input="setEndDateAsStartDate"
                                />
                            </div>
                            <div class="col-lg-5 pl-lg-0 time-picker-dd-pos">
                                <app-input type="time" v-model="startTime" @input="setStart($event)"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-7 pr-lg-0">
                                <app-input
                                    type="date"
                                    :min-date="formData.started_at"
                                    :placeholder="$t('end_date')"
                                    v-model="formData.ended_at"
                                />
                            </div>
                            <div class="col-lg-5 pl-lg-0 time-picker-dd-pos">
                                <app-input type="time" v-model="endTime"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participants input -->
            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center">{{
                            $t("participants")
                        }}</label>
                    <div class="col-sm-10">
<!--                        old-->
<!--                        <app-input-->
<!--                            type="multi-select"-->
<!--                            :list="personList"-->
<!--                            list-value-field="name"-->
<!--                            v-model="formData.person_id"-->
<!--                            :is-animated-dropdown="true"-->
<!--                        />-->

                            <app-paginated-multi-select
                                :options="personSelectOptions"
                                v-model="formData.person_id"
                            />
                    </div>
                </div>
            </div>
            <!-- end of Participants input -->

            <!-- Collaborators input -->
            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center text-break">{{
                            $t("collaborators")
                        }}</label>
                    <div class="col-sm-10">
<!--                        <app-input-->
<!--                            type="multi-select"-->
<!--                            :list="ownerList"-->
<!--                            list-value-field="full_name"-->
<!--                            v-model="formData.owner_id"-->
<!--                            :is-animated-dropdown="true"-->
<!--                        />-->
                        <app-paginated-multi-select
                            :options="ownerSelectOptions"
                            v-model="formData.owner_id"
                        />
                    </div>
                </div>
            </div>
            <!-- Activity reminder -->
            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center">{{ $t("set_reminder") }}</label>
                    <div class="col-sm-5">
                        <app-input
                            type="select"
                            list-value-field="title"
                            :list="setReminderType"
                            :placeholder="$t('choose_your_activity_type')"
                            v-model="formData.reminder_type"
                        />
                        <small class="text-danger" v-if="errors.reminder_type">{{
                                errors.reminder_type[0]
                            }}</small>
                    </div>
                    <div class="col-sm-5" v-if="formData.reminder_type == 'custom'">
                        <app-input
                            type="date"
                            date-mode="dateTime"
                            :min-date="new Date()"
                            :max-date="formData.started_at"
                            :placeholder="$t('set_reminder')"
                            v-model="formData.reminder_on"
                        />
                        <small class="text-danger" v-if="errors.reminder_on">{{
                                errors.reminder_on[0]
                            }}</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-10">
                        <app-note :notes="reminderNotification"
                                  :show-title="false"
                                  :content-type="'html'"
                                  class="mt-2"
                                  note-type="note-warning"/>
                    </div>
                </div>
            </div>
            <!-- end of Collaborators input -->
            <div class="form-group">
                <div class="form-row">
                    <label class="mb-0 col-sm-2 d-flex align-items-center">{{
                            $t("save_as_done")
                        }}</label>
                    <div class="mt-2 col-sm-10">
                        <app-input
                            type="single-checkbox"
                            :list-value-field="$t(' ')"
                            v-model="formData.activity_done"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-primary px-primary border-top mx-minus-primary">
            <button type="button" class="btn btn-primary" @click.prevent="submitData">
        <span class="w-100">
          <app-submit-button-loader v-if="loading"></app-submit-button-loader>
        </span>
                <template v-if="!loading">{{ $t("save") }}</template>
            </button>
        </div>
    </form>
</template>

<script>
import {FormMixin} from "@core/mixins/form/FormMixin";
import {mapGetters} from "vuex";
import {collect} from "@app/Helpers/Collection";
import {formatted_time, time_format} from "@app/Helpers/helpers";
import moment from "moment";
import {formatDateTimeForServer} from "../../../Helpers/helpers";

export default {
    name: "ActivityTab",
    props: ["props"],
    mixins: [FormMixin],
    data() {
        return {
            reminderNotification: [
                `${this.$t('activity_cron_job_note')} <a href="https://pipex.gainhq.com/documentation/important-settings.html#scheduler-queue" target="_blank">
                                <app-icon name="alert-circle" class="size-18 mr-2"/>
                                ${this.$t('see_documentation')}</a>`
            ],
            formatted_time,
            activityTypeList: [],
            loading: false,
            activity_type_id: null,
            doneActivityId: null,
            errors: {},
            endTime: moment(new Date()).format(`${time_format()}`),
            startTime: moment(new Date()).subtract("15", "minutes").format(`${time_format()}`),
            formData: {
                title: "",
                started_at: new Date(),
                ended_at: new Date(),
                person_id: [],
                owner_id: [],
            },
            ownerSelectOptions: {
                // url: route('crm.auth_user'),
                url: route('selectable.owners'),
                listValueField: "full_name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
            },
            personSelectOptions: {
                url: route('selectable.persons'),
                listValueField: "name",
                per_page: 10,
                loader: "app-pre-loader", // by default 'app-overlay-loader'
            }
        };
    },
    computed: {
        ...mapGetters({
            ownerList: "getOwner",
            organizationList: "getOrganization",
            personList: "getPerson",
            statusList: "getActivityStatus",
        }),
        setReminderType() {
            return [
                {
                    id: '',
                    title: this.$t("none"),
                },
                {
                    id: '15mins',
                    title: this.$t("15mins"),
                },
                {
                    id: '1hr',
                    title: this.$t("1hr"),
                },
                {
                    id: '1day',
                    title: this.$t("1day"),
                },
                {
                    id: 'custom',
                    title: this.$t("custom"),
                },
            ];
        },
    },
    created() {
    },
    methods: {
        setStart(v) {
            this.startTime = v;
            this.formData.start_time = v;
            if (
                this.formData.started_at.toDateString() == this.formData.ended_at.toDateString()
            ) {
                this.endTime =
                    this.convertTime12to24(v) > this.convertTime12to24(this.endTime)
                        ? this.startTime
                        : this.endTime;
            }
        },
        convertTime12to24(time12h) {
            if (formatted_time() == 24) {
                return time12h;
            }
            const [time, modifier] = time12h.split(" ");

            let [hours, minutes] = time.split(":");

            if (hours === "12") {
                hours = "00";
            }

            if (modifier === "PM") {
                hours = parseInt(hours, 10) + 12;
            }

            return `${hours}:${minutes}`;
        },
        setEndDateAsStartDate() {
            // please no need to change this formation of date,
            // it is just use for check logic

            let s = moment(moment(this.formData.started_at).format("YYYY-MM-DD")),
                e = moment(moment(this.formData.ended_at).format("YYYY-MM-DD")),
                diff = e.diff(s, "days");

            if (diff < 0) {
                this.formData.ended_at = this.formData.started_at;
            }
        },
        beforeSubmit() {
            this.loading = true;
        },
        submitData() {
            // let selectedStartDate = this.formData.started_at;
            // let selectedEndDate = this.formData.ended_at;
            let started_at = moment(this.formData.started_at).format(
                "YYYY-MM-DD"
            );
            let start_time = this.convertTime12to24(this.startTime);
            let start = formatDateTimeForServer(moment(started_at + ' ' + start_time, "YYYY-MM-DD HH:mm"))

            let ended_at = moment(this.formData.ended_at).format(
                "YYYY-MM-DD"
            );
            let end_time = this.convertTime12to24(this.endTime);
            let end = formatDateTimeForServer(moment(ended_at + ' ' + end_time, "YYYY-MM-DD HH:mm"))

            let activity = this.formData;
            activity.activity_type_id = this.activity_type_id;
            activity.title = this.formData.title;
            activity.description = this.formData.description;
            activity.reminder_type = this.formData.reminder_type;
            activity.reminder_on =  formatDateTimeForServer(this.formData.reminder_on)
            activity.started_at = moment(start).format(
                "YYYY-MM-DD"
            );
            activity.start_time = moment(start).format(
                "HH:mm"
            );
            activity.ended_at = moment(end).format(
                "YYYY-MM-DD"
            );
            activity.end_time = moment(end).format(
                "HH:mm"
            );
            activity.contextable_id = this.props.id;
            activity.contextable_type = this.props.contextable_type;
            activity.person_id = this.formData.person_id;
            activity.owner_id = this.formData.owner_id;

            if (this.formData.activity_done) {
                activity.status_id = collect(this.statusList)
                    .where("name", "=", "status_done")
                    .first().id;
            }

            this.save(activity);
            //
            // this.formData.started_at = selectedStartDate;
            // this.formData.ended_at = selectedEndDate;
        },
        afterError(response) {
            this.loading = false;
            this.errors = response.data.errors;
            this.$toastr.e(response.data.message);
        },

        afterSuccess(response) {
            let todoStatusId = collect(this.statusList)
                .where("name", "=", "status_todo")
                .first().id;
            let doneStatusId = collect(this.statusList)
                .where("name", "=", "status_done")
                .first().id;
            let status = this.formData.activity_done ? doneStatusId : todoStatusId;
            this.input = "";
            this.formData = {
                title: "",
                started_at: new Date(),
                ended_at: new Date(),
                person_id: [],
                owner_id: [],
            };
            this.activity_type_id = this.activityTypeList.length
                ? collect(this.activityTypeList).first().id
                : null;
            this.$toastr.s(response.data.message);
            this.$hub.$emit("activity-list", `status=${status}`);
        },
        afterFinalResponse() {
            this.loading = false;
        },

        getActivityType() {
            return this.axiosGet(route("activity_types.index"))
                .then((response) => {
                    this.activityTypeList = this.collection(response.data.data).shaper(
                        "translated_name"
                    );
                    this.activity_type_id = this.activityTypeList.length
                        ? collect(this.activityTypeList).first().id
                        : null;
                })
                .catch((error) => {
                    console.log(error);
                });
        },
    },
    mounted() {
        this.$store.dispatch("getActivityStatus");
        this.getActivityType();
    },
};
</script>
