import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    phoneEmailTypeLists: []
};
const getters = {
    phoneEmailType: state => state.phoneEmailTypeLists

};

const mutations = {
    PHONE_EMAIL_TYPE_INFO(state, data) {
        state.phoneEmailTypeLists = data
    }
};

const actions = {
    phoneEmailType(context) {
        axiosGet(route('phone_email.type')).then((response) => {
            let data = response.data;
            data.forEach((item) => {
                item.name = this._vm.$t(item.name);
            })
            context.commit('PHONE_EMAIL_TYPE_INFO', data)
        }).catch((error) => console.log(error));
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
