<script setup>
import { Head, Link } from '@inertiajs/vue3';

const features = [
  {
    icon: 'bx-chalkboard',
    title: "Class Management",
    description: "Easily create and organize your classes with student lists and attendance records."
  },
  {
    icon: 'bx-user-check',
    title: "Attendance Tracking",
    description: "Quickly mark attendance with just a few clicks and view historical records."
  },
  {
    icon: 'bx-bar-chart-alt-2',
    title: "Reports & Analytics",
    description: "Generate detailed reports on student attendance patterns and trends."
  },
  {
    icon: 'bx-rocket',
    title: "Simple & Intuitive",
    description: "Designed for teachers with a clean, easy-to-use interface that saves time."
  }
];

const testimonials = [
  {
    quote: "This app has completely transformed how I track attendance. It saves me hours every week!",
    author: "Sarah Johnson, High School Teacher"
  },
  {
    quote: "Finally an attendance system that's actually designed for teachers. Simple and effective.",
    author: "Michael Chen, College Professor"
  }
];
</script>

<template>
  <Head title="Student Attendance Tracker" />

  <div class="min-h-screen bg-gradient-to-b from-blue-50 to-white">
    <!-- Hero Section -->
    <div class="relative overflow-hidden">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative z-10 py-16 md:py-24 lg:py-32">
          <div class="text-center">
            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 sm:text-5xl md:text-6xl">
              <span class="block">Simplify Your</span>
              <span class="block text-blue-600">Attendance Tracking</span>
            </h1>
            <p class="mt-6 max-w-lg mx-auto text-lg text-gray-600">
              A modern, intuitive solution for teachers to manage student attendance with ease. Save time and focus on what matters most - teaching.
            </p>
            <div class="mt-10 flex justify-center gap-4">
              <Link href="/register" class="px-6 py-3 rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 text-base font-medium md:py-4 md:text-lg md:px-8">
                Get Started
              </Link>
              <Link href="/login" class="px-6 py-3 rounded-md shadow-sm text-blue-700 bg-blue-100 hover:bg-blue-200 text-base font-medium md:py-4 md:text-lg md:px-8">
                Sign In
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">Features</h2>
          <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Everything you need to track attendance
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            Designed specifically for educators to save time and reduce paperwork.
          </p>
        </div>

        <div class="mt-16">
          <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4">
            <div v-for="feature in features" :key="feature.title" class="pt-8">
              <div class="flow-root bg-gray-50 rounded-lg px-6 pb-8 h-full">
                <div class="-mt-6">
                  <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                    <i class="bx" :class="feature.icon"></i>
                  </div>
                  <h3 class="mt-8 text-lg font-medium text-gray-900 text-center">{{ feature.title }}</h3>
                  <p class="mt-4 text-base text-gray-500 text-center">
                    {{ feature.description }}
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- How It Works Section -->
    <div class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-base text-blue-600 font-semibold tracking-wide uppercase">How it works</h2>
          <p class="mt-2 text-3xl font-extrabold text-gray-900 sm:text-4xl">
            Get started in minutes
          </p>
          <p class="mt-4 max-w-2xl text-xl text-gray-500 mx-auto">
            Our simple 3-step process makes attendance tracking effortless
          </p>
        </div>

        <div class="mt-16">
          <div class="grid gap-8 md:grid-cols-3">
            <div class="bg-white p-6 rounded-lg shadow-sm">
              <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                <span class="text-lg font-bold">1</span>
              </div>
              <h3 class="mt-6 text-lg font-medium text-gray-900 text-center">Create Your Class</h3>
              <p class="mt-4 text-base text-gray-500 text-center">
                Set up your class with a name and description. Create multiple classes for different subjects.
              </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
              <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                <span class="text-lg font-bold">2</span>
              </div>
              <h3 class="mt-6 text-lg font-medium text-gray-900 text-center">Add Your Students</h3>
              <p class="mt-4 text-base text-gray-500 text-center">
                Import your student list or add them manually with contact details.
              </p>
            </div>

            <div class="bg-white p-6 rounded-lg shadow-sm">
              <div class="flex items-center justify-center h-12 w-12 rounded-md bg-blue-500 text-white mx-auto">
                <span class="text-lg font-bold">3</span>
              </div>
              <h3 class="mt-6 text-lg font-medium text-gray-900 text-center">Track Attendance</h3>
              <p class="mt-4 text-base text-gray-500 text-center">
                Mark attendance daily with our simple interface and view reports.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Testimonials -->
    <div class="py-16 bg-blue-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
            Trusted by educators
          </h2>
        </div>

        <div class="mt-12 grid gap-8 md:grid-cols-2">
          <div v-for="testimonial in testimonials" :key="testimonial.author" class="bg-white p-8 rounded-lg shadow-sm">
            <div class="flex items-center">
              <div class="flex-shrink-0 bg-blue-500 rounded-full p-2">
                <i class="bx bx-chat text-white text-xl"></i>
              </div>
              <div class="ml-4">
                <p class="text-gray-600 italic">"{{ testimonial.quote }}"</p>
                <p class="mt-2 font-medium text-gray-900">{{ testimonial.author }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-blue-700 rounded-lg shadow-xl overflow-hidden">
          <div class="px-6 py-12 sm:p-16">
            <div class="md:flex md:items-center md:justify-between">
              <div class="md:w-2/3">
                <h2 class="text-2xl font-extrabold text-white sm:text-3xl">
                  Ready to simplify your attendance tracking?
                </h2>
                <p class="mt-4 max-w-2xl text-blue-100">
                  Join thousands of educators who save time with our attendance tracker.
                </p>
              </div>
              <div class="mt-8 md:mt-0 md:w-1/3">
                <div class="rounded-md shadow">
                  <Link href="/register" class="w-full flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 md:py-4 md:text-lg">
                    Get Started Free
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
          <div class="col-span-2">
            <h3 class="text-white text-xl font-bold">AttendTrack</h3>
            <p class="mt-4 text-gray-300">
              The simple, powerful solution for teachers to track student attendance.
            </p>
          </div>

          <div>
            <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">
              Product
            </h3>
            <div class="mt-4 space-y-4">
              <Link href="/features" class="text-gray-400 hover:text-white block">
                Features
              </Link>
              <Link href="/pricing" class="text-gray-400 hover:text-white block">
                Pricing
              </Link>
            </div>
          </div>

          <div>
            <h3 class="text-sm font-semibold text-gray-300 tracking-wider uppercase">
              Support
            </h3>
            <div class="mt-4 space-y-4">
              <Link href="/help" class="text-gray-400 hover:text-white block">
                Help Center
              </Link>
              <Link href="/contact" class="text-gray-400 hover:text-white block">
                Contact Us
              </Link>
            </div>
          </div>
        </div>

        <div class="mt-12 border-t border-gray-700 pt-8">
          <p class="text-gray-400 text-center text-sm">
            &copy; 2023 AttendTrack. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  </div>
</template>
