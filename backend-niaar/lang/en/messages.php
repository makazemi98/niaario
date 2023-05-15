<?php

return [
    'inquiries' => [
        'assign' => [
            'failed' => 'Assign inquiry failed.',
            'success' => 'Assign inquiry successful.'
        ],

        // Used for change status comments
        'comments' => [
            'assign' => 'ASSIGNED the inquiry to :user_full_name',
            'changed' => ':user_full_name has changed this inquiry status from :old_status to :new_status',
            're_assign' => "Reassigned this inquiry to :user_full_name \n"
                . "Reason: :reason",
            'cancel' => "Cancelled this inquiry by :user_full_name \n"
                . "Reason: :reason",
            'add_file' => [
                'docs' => ':file_name added to DOCS folder',
                'confidential' => 'Uploaded :file_name to Confidential.'
            ],
        ],

        // Used for http response message
        'change_status' => [
            'success' => 'Inquiry status successfully changed.',
            'failed' => 'Change inquiry status failed.'
        ],
        'create' => [
            'failed' => 'Create inquiry error. Please try again.',
            'success' => 'Inquiry created successfully.'
        ],
        'actions' => [
            'cancel' => [
                'success' => 'Cancel inquiry successful.',
                'failed' => 'Cancel inquiry failed'
            ],
            're_assign' => [
                'success' => 'Reassigned inquiry successful.',
                'failed' => 'Reassigned inquiry failed'
            ],
        ],
        'upload_doc' => [
            'success' => 'Documents uploaded successfully.',
            'failed' => 'An error has occurred. Documents could not be uploaded.'
        ],
        'calculations' => [
            'store' => [
                'success' => 'Calculation data successfully stored.',
                'failed' => 'Store calculation data failed.'
            ],
            'update' => [
                'success' => 'Calculation data successfully updated.',
                'failed' => 'Update calculation data failed.'
            ]
        ],
        'future_dues' => [
            'store' => [
                'success' => 'Future due data successfully stored.',
                'failed' => 'Store future due data failed.'
            ],
            'update' => [
                'success' => 'Future due data successfully updated.',
                'failed' => 'Update future due data failed.'
            ],
            'payment_status' => [
                'success' => 'Status change was done successfully.',
                'not_allowed' => 'Change status not allowed'
            ]
        ]
    ],
    'users' => [
        'create' => [
            'failed' => 'Store user error. Please try again.',
            'success' => 'User created successfully.'
        ],
        'update' => [
            'failed' => 'Update user error. Please try again.',
            'success' => 'User updated successfully.'
        ],

        'delete' => [
            'failed' => 'Deleting this user is not allowed.',
            'success' => 'User deleted successfully.'
        ],
    ],
    'reminders' => [
        'not_found' => "Reminder not found.",
        'create' => [
            'failed' => 'Create reminder error. Please try again.',
            'success' => 'Reminder created successfully.'
        ],
    ],
    'suppliers' => [
        'create' => [
            'success' => 'Create supplier successful.',
            'failed' => 'Create supplier failed.'
        ],
        'update' => [
            'success' => 'Supplier data has been updated successfully.',
            'failed' => 'Update supplier failed.'
        ]
    ],
    'notifications' => [
        'user_mentioned' => "You are mentioned at :inquiry_title",
        'not_found' => "Notification not found.",
        'delete' => [
            'success' => 'Delete notification successful.'
        ]
    ],
    'shipping' => [
        'store' => [
            'success' => 'Shipping data has been stored.',
            'failed' => 'Store shipping error. Shipping not stored.'
        ],
        'update' => [
            'success' => 'Shipping data has been updated.',
            'failed' => 'Update shipping error.'
        ],
        'boxes' => [
            'store' => [
                'success' => 'Box data has been saved.',
                'failed' => 'Store box data error. Please try again.'
            ],
            'update' => [
                'success' => 'Update successful.',
                'failed' => 'Update error.'
            ],
            'delete' => [
                'success' => 'Delete resource successful.',
                'failed' => 'Delete resource error.'
            ]
        ],
        'comments' => [
            'store' => [
                'success' => 'Comment has been saved.',
                'failed' => 'Store comment error. Please try again.'
            ],
            'update' => [
                'success' => 'Update comment successful.',
                'failed' => 'Update comment error.'
            ]
        ]
    ],
];
