import queryString from "query-string";

export default {
    methods: {
        filteredValues(values) {
          this.setUrlSearhParams(values);
        },
        setUrlSearhParams(values) {
          let params = queryString.stringify(values, {
            arrayFormat: "comma",
          });
          var newurl =
            window.location.protocol +
            "//" +
            window.location.host +
            window.location.pathname +
            "?" +
            params;
          window.history.pushState({ path: newurl }, "", newurl);
        },
        setFilterInit() {
          console.log(queryString.parse(window.location.search));
          let filtersObj = queryString.parse(window.location.search);
          this.options.filters.forEach((e) => (e.initValue = filtersObj[e.key]));
        }
    },
    created() {
        this.setFilterInit();
      },
}