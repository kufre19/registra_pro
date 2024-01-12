<template>
    <div>
        <a v-if="rowData.contextable_type === 'App\\Models\\CRM\\Deal\\Deal'"
           @click="redirectToRoute(route('deal_details.page', {'id': rowData.contextable_id}))"
           :href="route('deal_details.page', {'id': rowData.contextable_id})">
            {{ rowData.title }}
        </a>

        <a v-if="rowData.contextable_type === 'App\\Models\\CRM\\Person\\Person'"
           @click="redirectToRoute(route('persons.edit', {'id': rowData.contextable_id}))"
           :href="route('persons.edit', {'id': rowData.contextable_id})">
            {{ rowData.title }}
        </a>

        <a v-if="rowData.contextable_type === 'App\\Models\\CRM\\Organization\\Organization'"
           @click="redirectToRoute(route('organizations.edit', {'id': rowData.contextable_id}))"
           :href="route('organizations.edit', {'id': rowData.contextable_id})">
            {{ rowData.title }}
        </a>

    </div>

</template>

<script>

export default {
    name: "ActivityDetailsPageLink",
    props: {
        rowData: {},
    },
    data() {
        return {
            route
        }
    },
  methods:{
    redirectToRoute(path){
        window.open(path);
    }
  },
  mounted() {
      // console.log('rowData', this.rowData);
  }
}
</script>

