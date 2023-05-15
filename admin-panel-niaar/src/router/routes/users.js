export default [
  {
    path: '/admin-users',
    name: 'admin-users-index',
    component: () => import('@/views/users/AdminUsersList.vue'),
  },
  {
    path: '/messages',
    name: 'messages-index',
    component: () => import('@/views/users/MessagesList.vue'),
  },
  {
    path: '/users/seller-approval-form',
    name: 'seller-approval-form-index',
    component: () => import('@/views/users/SellerApprovalForm.vue'),
  },
  {
    path: '/users/seller-approval-requests',
    name: 'seller-approval-requests-index',
    component: () => import('@/views/users/SellerApprovalRequests.vue'),
  },
  {
    path: '/user-gpdr-requests',
    name: 'user-gpdr-requests-index',
    component: () => import('@/views/users/UserGpdrRequests.vue'),
  }
]
