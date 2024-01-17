import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    noteList: [],
};
const getters = {
    getNote: state => state.noteList
};

const mutations = {
    NOTE_INFO(state, data) {
        state.noteList = data
    }
};

const actions = {
    getNote({commit}) {
        axiosGet(route('note_list')).then(({data}) => {
            console.log(data);
            commit('NOTE_INFO', data)
        }).catch((error) => console.log(error))
    }
};


export default {
    state,
    getters,
    mutations,
    actions
}
