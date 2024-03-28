<template>
  <Head title="Create Student" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create Student</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <form @submit.prevent="submitForm">
              <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  type="text"
                  id="name"
                  name="name"
                  v-model="form.name"
                  class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                />
              </div>
              <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Select Image</label>
              <div
                class="image-preview border p-0 mb-4 cursor-pointer"
                @click="$refs.fileInput.click()"
              >
                <img
                  v-if="form.imageUrl || (props.student && !form.image)"
                  :src="form.imageUrl ? form.imageUrl : '/images/' + props.student.image"
                  alt="Image Preview"
                  class="image-preview"
                />
                <span v-else>No Image Selected</span>

                <input
                  type="file"
                  ref="fileInput"
                  id="image"
                  name="image"
                  @change="handleFileUpload"
                  class="hidden"
                />
              </div>

              <div class="mb-4">
                <label for="age" class="block text-sm font-medium text-gray-700">Age</label>
                <input
                  type="number"
                  id="age"
                  name="age"
                  v-model="form.age"
                  class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                />
              </div>
              <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <select
                  id="status"
                  name="status"
                  v-model="form.status"
                  class="mt-1 p-2 border border-gray-300 rounded-md w-full"
                >
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
              <button
                v-if="props.student"
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >Update</button>
              <button
                v-else
                type="submit"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
              >Save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
  
<script setup>
import { useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

const props = defineProps(["student"]);

const form = useForm({
  id: props.student ? props.student.id : "",
  name: props.student ? props.student.name : "",
  age: props.student ? props.student.age : null,
  image: null,
  status: props.student ? props.student.status : null
});

function handleFileUpload(event) {
  const file = event.target.files[0];
  form.image = file;
  // Generate image URL for preview
  if (file) {
    const reader = new FileReader();
    reader.onload = e => {
      form.imageUrl = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    form.imageUrl = props.student ? props.student.imageUrl : null;
  }
}

async function submitForm() {
  form.processing = true;

  try {
    const response = await form.post(
      props.student ? route("student.update") : route("student.store"),
      {
        onSuccess: () => {
          form.reset();
        },
        onError: () => {
          form.reset();
        }
      }
    );
  } catch (error) {
    console.error("Error submitting form:", error);
  } finally {
    form.processing = false; // Reset processing status after form submission
  }
}
</script>
<style scoped>
/* Optional: Add scoped styles to adjust image box size */
.image-preview {
  width: 100px; /* Adjust width as needed */
  height: 100px; /* Adjust height as needed */
}
</style>

  