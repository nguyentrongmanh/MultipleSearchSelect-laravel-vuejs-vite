<template>
  <div class="w-full flex flex-col items-center h-64 mx-auto">
    <div class="inline-block relative w-4/5">
      <div class="flex flex-col items-center relative">
        <div class="w-full">
          <div
            class="my-2 p-1 flex border border-indigo-600 border-2 bg-white rounded rounded-lg"
          >
            <div class="flex flex-auto flex-wrap">
              <template v-for="(tag, index) in selectedTag" :key="index">
                <div
                  class="flex justify-center items-center m-1 font-medium py-1 px-2 bg-white rounded bg-gray-200 border rounded-xl"
                >
                  <div
                    class="text-xs font-normal leading-none max-w-full flex-initial x-model="
                  >
                    {{ tag }}
                  </div>
                  <div class="flex flex-auto flex-row-reverse">
                    <div @click="removeTag(index, tag)">
                      <svg
                        class="fill-current h-4 w-4"
                        role="button"
                        viewBox="0 0 20 20"
                      >
                        <path
                          d="M14.348,14.849c-0.469,0.469-1.229,0.469-1.697,0L10,11.819l-2.651,3.029c-0.469,0.469-1.229,0.469-1.697,0
                                           c-0.469-0.469-0.469-1.229,0-1.697l2.758-3.15L5.651,6.849c-0.469-0.469-0.469-1.228,0-1.697s1.228-0.469,1.697,0L10,8.183
                                           l2.651-3.031c0.469-0.469,1.228-0.469,1.697,0s0.469,1.229,0,1.697l-2.758,3.152l2.758,3.15
                                           C14.817,13.62,14.817,14.38,14.348,14.849z"
                        />
                      </svg>
                    </div>
                  </div>
                </div>
              </template>
              <div class="flex-1">
                <input
                  placeholder="Select a option"
                  class="bg-transparent p-1 px-2 appearance-none outline-none h-full w-full text-gray-800"
                  v-model="searchTag"
                />
              </div>
            </div>
            <div
              class="text-gray-300 w-8 py-1 pl-2 pr-1 border-l flex items-center border-gray-200 svelte-1l8159u"
            >
              <button
                type="button"
                v-if="isOpen"
                @click="toggleOpen"
                class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none"
              >
                <svg
                  version="1.1"
                  class="fill-current h-4 w-4"
                  viewBox="0 0 20 20"
                >
                  <path
                    d="M17.418,6.109c0.272-0.268,0.709-0.268,0.979,0s0.271,0.701,0,0.969l-7.908,7.83
	c-0.27,0.268-0.707,0.268-0.979,0l-7.908-7.83c-0.27-0.268-0.27-0.701,0-0.969c0.271-0.268,0.709-0.268,0.979,0L10,13.25
	L17.418,6.109z"
                  />
                </svg>
              </button>
              <button
                type="button"
                v-if="!isOpen"
                @click="toggleOpen"
                class="cursor-pointer w-6 h-6 text-gray-600 outline-none focus:outline-none"
              >
                <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                  <path
                    d="M2.582,13.891c-0.272,0.268-0.709,0.268-0.979,0s-0.271-0.701,0-0.969l7.908-7.83
	c0.27-0.268,0.707-0.268,0.979,0l7.908,7.83c0.27,0.268,0.27,0.701,0,0.969c-0.271,0.268-0.709,0.268-0.978,0L10,6.75L2.582,13.891z
	"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>
        <div
          class="w-full px-4"
          v-if="isOpen && !showAlert && availableTags.length > 0"
        >
          <div
            class="absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select"
          >
            <div class="flex flex-col w-full overflow-y-auto h-64">
              <template v-for="(tag, index) in availableTags" :key="index">
                <div
                  class="cursor-pointer w-full border-gray-100 rounded-t border-b hover:bg-gray-100"
                  @click="selectTag(tag)"
                >
                  <div
                    class="flex w-full items-center p-2 pl-2 border-transparent border-l-2 relative"
                  >
                    <div class="w-full items-center flex justify-between">
                      <div class="mx-2 leading-6">{{ tag }}</div>
                    </div>
                  </div>
                </div>
              </template>
            </div>
          </div>
        </div>
        <div class="w-full px-5" v-if="showAlert">
          <div
            class="px-5 cursor-pointer py-2 flex justify-start items-center gap-x-3 absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select h-12"
          >
            This value is already selected
          </div>
        </div>
        <div class="w-full px-5" v-if="showAddTagButton">
          <div
            class="px-5 bg-indigo-200 cursor-pointer py-2 flex justify-start items-center gap-x-3 absolute shadow top-100 bg-white z-40 w-full left-0 rounded max-h-select h-12"
            @click="addTag"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="16"
              height="16"
              viewBox="0 0 16 16"
            >
              <circle cx="8" cy="8" r="7" fill="none" stroke="#000" />
              <path
                d="M8,4.5V11.5M4.5,8H11.5"
                stroke="#000"
                stroke-width="1.5"
              />
            </svg>
            Add Tag
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import axios from "axios";
import {
  defineComponent,
  ref,
  reactive,
  computed,
  watch,
  defineProps,
} from "vue";

const updateTagToServer = (name: string, is_selected: boolean) => {
  axios.post("/tag/update", {
    name: name,
    is_selected: is_selected,
  });
};

const props = defineProps<{
  selectedTags: Array<string>;
  tags: Array<string>;
}>();

// state
const isOpen = ref(false);
const searchTag = ref("");
const tags: Array<string> = reactive(props.tags);
const selectedTag: Array<string> = reactive(props.selectedTags);

// function
const toggleOpen: () => void = () => {
  isOpen.value = !isOpen.value;
};
const removeTag = (index: number, tag: string) => {
  updateTagToServer(tag, false);
  selectedTag.splice(index, 1);
};
const selectTag = (tag: string) => {
  updateTagToServer(tag, true);
  selectedTag.push(tag);
};
const addTag = () => {
  selectedTag.push(searchTag.value);
  tags.push(searchTag.value);
  updateTagToServer(searchTag.value, true);
  searchTag.value = "";
};

// computed
const availableTags = computed(() => {
  return tags.filter(
    (tag) => !selectedTag.includes(tag) && tag.includes(searchTag.value)
  );
});
const showAlert = computed(() => {
  return selectedTag.includes(searchTag.value);
});
const showAddTagButton = computed(() => {
  return (
    availableTags.value.length == 0 &&
    !showAlert.value &&
    searchTag.value.length > 0
  );
});

watch(searchTag, (_) => {
  isOpen.value = true;
});
</script>
<style scoped>
[x-cloak] {
  display: none;
}

.svg-icon {
  width: 1em;
  height: 1em;
}

.svg-icon path,
.svg-icon polygon,
.svg-icon rect {
  fill: #333;
}

.svg-icon circle {
  stroke: #4691f6;
  stroke-width: 1;
}
</style>
