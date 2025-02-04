import { watch, ref } from "vue";

export default function useSearch() {
    const searchQuery = ref("");
    const results = ref([]);
    const performSearch = async () => {
        const response = await axios.get("/search_product", {
            params: { query: searchQuery.value },
        });
        results.value = response.data;
    };
    watch(searchQuery, (newQuery) => {
        performSearch();
    });
    return {
        searchQuery,
        results,
        performSearch,
    };
}
