import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    typeLists: []
};
const getters = {
    contentType: state => state.typeLists

};

const mutations = {
    CONTACT_TYPE_INFO(state, data) {
        state.typeLists = data
    }
};

const actions = {
    contentType(context) {
        axiosGet(route('types.index', {_query: {all: true}})).then((response) => {
            context.commit('CONTACT_TYPE_INFO', response.data)
        }).catch((error) => console.log(error));
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
