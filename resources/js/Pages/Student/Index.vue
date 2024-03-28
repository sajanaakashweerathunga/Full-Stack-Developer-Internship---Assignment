<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, usePage } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
const { $inertia } = usePage();
const students = ref([]);

const editStudent = studentId => {
  window.location.href = route("student.edit", { id: studentId });
};
const deleteStudent = async studentId => {
  try {
    await axios.delete(route("student.delete", { id: studentId }));
    // Optionally update the UI by removing the deleted student from the list
    students.value = students.value.filter(student => student.id !== studentId);
  } catch (error) {
    console.error("Error deleting student:", error);
  }
};
onMounted(async () => {
  try {
    const response = await axios.get(route("student.getdata"));
    students.value = response.data;
  } catch (error) {
    console.error("Error fetching students:", error);
  }
});
const toggleStatus = async student => {
  try {
    const newStatus = student.status === 1 ? 0 : 1;
    await axios.put(route("student.toggleStatus", { id: student.id }), {
      status: newStatus
    });
    student.status = newStatus; // Update the status locally without fetching all students again
  } catch (error) {
    console.error("Error toggling status:", error);
  }
};
</script>

<template>
  <Head title="Student" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Student</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <Link class="btn btn-primary mb-3" :href="route('student.create')">Create</Link>

            <!-- Table to display student data -->
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >ID</th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >Name</th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >Age</th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >Image</th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >Status</th>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                  >Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <!-- Loop through students data and display each row -->
                <tr v-for="student in students" :key="student.id">
                  <td class="px-6 py-4 whitespace-nowrap">{{ student.id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ student.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ student.age }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <img :src="'/images/' + student.image" alt="Student Image" class="h-12 w-12 object-cover rounded-full">
                  </td>
                  <td
                    class="px-6 py-4 whitespace-nowrap"
                    :class="{ 'text-green-600': student.status === 1, 'text-red-600': student.status === 0 }"
                  >{{ student.status === 1 ? 'Active' : 'Inactive' }}</td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <button @click="editStudent(student.id)" class="btn btn-primary me-2">Edit</button>
                    <button @click="deleteStudent(student.id)" class="btn btn-danger me-2">Delete</button>
                    <button
                      @click="toggleStatus(student)"
                      class="btn btn-secondary me-2"
                      style="width: 150px;"
                    >{{ student.status === 1 ? 'Deactivate' : 'Activate' }}</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
