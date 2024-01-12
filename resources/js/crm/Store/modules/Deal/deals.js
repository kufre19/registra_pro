import {axiosGet} from "../../../Helpers/AxiosHelper";

const state = {
    DealList: [],
    DealListsByOwnerId: [],
    DealDetailsById: {},
    dealId: window.localStorage.getItem('dealId'),
    dealIdClear: window.localStorage.removeItem('dealId')
};

const getters = {
    getDeal: state => state.DealList,
    setProposalDealId: state => state.dealId,
    getDealListsByOwnerID: state => state.DealListsByOwnerId,
    getDealDetailsByID: state => state.DealDetailsById,
};

const actions = {
    getDeal({commit}){
        axiosGet(route('deals.index', {_query: {all: true}})).then(({data}) => {
            commit('DEAL_INFO', data)
        }).catch((error) => console.log(error))
    },
    setProposalDealId({commit}, payload){
        commit('SET_DEAL_ID', payload)
    },
    clearDealID({commit}){
        commit('CLEAR_DEAL_ID')
    },
    async getDealsByOwnerId({commit}, {owner_id}){
        await axiosGet(route('deals.owner',{owner_id}))
            .then(({data}) => {
                commit('DEAL_INFO_BY_OWNER', data);
            }).catch((error) => console.log(error))
    },
    async getDealDetailsById({commit}, {id}){
        await axiosGet(route('deal.details',{id}))
            .then(({data}) => {
                commit('DEAL_INFO_BY_ID', data);
            }).catch((error) => console.log(error))
    },
};

const mutations = {
    DEAL_INFO(state, data) {
        state.DealList = data;
    },
    SET_DEAL_ID(state, payload){
        state.dealId = payload;
        window.localStorage.setItem('dealId', payload);
    },
    CLEAR_DEAL_ID(state){
        state.dealIdClear = '';
    },
    DEAL_INFO_BY_OWNER(state, data) {
        state.DealListsByOwnerId = data;
    },
    DEAL_INFO_BY_ID(state, data) {
        state.DealDetailsById = data;
    },
};


export default {
    state,
    getters,
    actions,
    mutations
}
