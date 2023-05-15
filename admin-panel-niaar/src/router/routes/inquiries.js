export default [
  {
    path: '/inquiries',
    name: 'inquiries',
    component: () => import('@/views/inquiries/index.vue'),
  },
  {
    path: '/inquiries/create',
    name: 'inquiries-create',
    component: () => import('@/views/inquiries/create.vue'),
  },
  {
    path: '/inquiries/:id',
    name: 'inquiry',
    props:true,
    component: () => import('@/views/inquiries/inquiry.vue'),
  },
]
