<?php

return [
		'config' => [		'title' => 'Config',		'fields' => [		],	],
		'roles' => [		'title' => 'Roles',		'fields' => [			'title' => 'Title',		],	],
		'users' => [		'title' => 'Users',		'fields' => [			'name' => 'Name',			'email' => 'Email',			'password' => 'Password',			'role' => 'Role',			'remember-token' => 'Remember token',		],	],
		'categories' => [		'title' => 'Categories',		'fields' => [			'title' => 'Title',		],	],
		'groups' => [		'title' => 'Groups',		'fields' => [			'title' => 'Title',		],	],
		'institutions' => [		'title' => 'Institutions',		'fields' => [			'title' => 'Title',		],	],
		'design' => [		'title' => 'Design',		'fields' => [		],	],
		'design' => [		'title' => 'Design',		'fields' => [		],	],
		'design' => [		'title' => 'Design',		'fields' => [		],	],
		'design' => [		'title' => 'Design',		'fields' => [		],	],
		'design' => [		'title' => 'Design',		'fields' => [		],	],
		'design' => [		'title' => 'Design',		'fields' => [		],	],
		'answerlists' => [		'title' => 'Answerlists',		'fields' => [			'title' => 'Title',			'type' => 'Type',			'answers' => 'Answers',		],	],
		'answers' => [		'title' => 'Answers',		'fields' => [			'title' => 'Title',			'open' => 'Open',		],	],
		'questions' => [		'title' => 'Questions',		'fields' => [			'title' => 'Title',			'answerlist' => 'Answerlist',		],	],
		'surveys' => [		'title' => 'Surveys',		'fields' => [			'title' => 'Title',			'alias' => 'Alias',			'institution' => 'Institution',			'category' => 'Category',			'group' => 'Group',			'introduction' => 'Introduction',			'notes' => 'Notes',			'access' => 'Access',			'completed' => 'Completed',		],	],
		'questionnaires' => [		'title' => 'Questionnaires',		'fields' => [			'survey' => 'Survey',			'name' => 'Name',		],	],
		'responses' => [		'title' => 'Responses',		'fields' => [			'questionnaire' => 'Questionnaire',			'question' => 'Question',			'answer' => 'Answer',			'content' => 'Content',		],	],
		'items' => [		'title' => 'Items',		'fields' => [			'survey' => 'Survey',			'question' => 'Question',			'label' => 'Label',			'order' => 'Order',		],	],
		'content' => [		'title' => 'Content',		'fields' => [		],	],
		'content-categories' => [		'title' => 'Categories',		'fields' => [		],	],
		'content-tags' => [		'title' => 'Tags',		'fields' => [		],	],
		'content-pages' => [		'title' => 'Pages',		'fields' => [		],	],
		'content' => [		'title' => 'Content',		'fields' => [		],	],
		'content-categories' => [		'title' => 'Categories',		'fields' => [			'title' => 'Category',			'slug' => 'Slug',		],	],
		'content-tags' => [		'title' => 'Tags',		'fields' => [			'title' => 'Tag',			'slug' => 'Slug',		],	],
		'content-pages' => [		'title' => 'Pages',		'fields' => [			'title' => 'Title',			'category-id' => 'Categories',			'tag-id' => 'Tags',			'page-text' => 'Text',			'excerpt' => 'Excerpt',			'featured-image' => 'Featured image',		],	],
		'activitylog' => [		'title' => 'Activitylog',		'fields' => [		],	],
		'activitylog' => [		'title' => 'Activitylog',		'fields' => [			'log-name' => 'Log name',			'causer-type' => 'Causer type',			'causer-id' => 'Causer id',			'description' => 'Description',			'subject-type' => 'Subject type',			'subject-id' => 'Subject id',			'properties' => 'Properties',		],	],
		'logs' => [		'title' => 'Logs',		'fields' => [		],	],
		'loguseragent' => [		'title' => 'Loguseragent',		'fields' => [			'os' => 'OS',			'os-version' => 'OS version',			'browser' => 'Browser',			'browser-version' => 'Browser version',			'device' => 'Device',			'language' => 'Language',			'item-id' => 'item_id',			'ipv6' => 'ipv6',			'uri' => 'Uri',			'form-submitted' => 'Form submitted',			'user' => 'User',		],	],
	'qa_create' => 'Δημιουργία',
	'qa_save' => 'Αποθήκευση',
	'qa_edit' => 'Επεξεργασία',
	'qa_view' => 'Εμφάνιση',
	'qa_update' => 'Ενημέρωση',
	'qa_list' => 'Λίστα',
	'qa_no_entries_in_table' => 'Δεν υπάρχουν δεδομένα στην ταμπέλα',
	'qa_custom_controller_index' => 'index προσαρμοσμένου controller.',
	'qa_logout' => 'Αποσύνδεση',
	'qa_add_new' => 'Προσθήκη νέου',
	'qa_are_you_sure' => 'Είστε σίγουροι;',
	'qa_back_to_list' => 'Επιστροφή στην λίστα',
	'qa_dashboard' => 'Dashboard',
	'qa_delete' => 'Διαγραφή',
	'qa_restore' => 'Επαναφορά',
	'qa_permadel' => 'Μόνιμη διαγραφή',
	'qa_all' => 'Όλα',
	'qa_trash' => 'διεγραμμένα',
	'qa_delete_selected' => 'Διαγραφή επιλεγμένων',
	'qa_category' => 'Κατηγορία',
	'qa_categories' => 'Κατηγορίες',
	'qa_sample_category' => 'Δείγμα κατηγορίας',
	'qa_questions' => 'Ερωτήσεις',
	'qa_question' => 'Ερώτηση',
	'qa_answer' => 'Απάντηση',
	'qa_sample_question' => 'Δείγμα ερώτησης',
	'qa_sample_answer' => 'Δείγμα απάντησης',
	'qa_administrator_can_create_other_users' => 'Διαχειριστής',
	'qa_simple_user' => 'Απλός χρήστης',
	'qa_title' => 'Τίτλος',
	'qa_roles' => 'Ρόλοι',
	'qa_role' => 'Ρόλος',
	'qa_user_management' => 'Διαχείριση χρηστών',
	'qa_users' => 'Χρήστες',
	'qa_user' => 'Χρήστης',
	'qa_name' => 'Όνομα',
	'qa_email' => 'Email',
	'qa_password' => 'Κωδικός',
	'qa_remember_token' => 'Να με θυμάσαι',
	'qa_permissions' => 'Δικαιώματα',
	'qa_user_actions' => 'Ενέργειες χρηστών',
	'qa_action' => 'Ενέργεια',
	'qa_description' => 'Περιγραφή',
	'qa_valid_from' => 'Από',
	'qa_valid_to' => 'Έως',
	'qa_coupons' => 'Κουπόνια',
	'qa_code' => 'Κώδικας',
	'qa_reports' => 'Αναφορές',
	'qa_work_type' => 'Τύπος εργασίας',
	'qa_start_time' => 'Ώρα εκκίνησης',
	'qa_end_time' => 'Ώρα τέλους',
	'qa_expenses' => 'Έξοδα',
	'qa_expense' => 'Έξοδο',
	'qa_amount' => 'Ποσό',
	'qa_income_categories' => 'Κατηγορίες Εισοδημάτων',
	'qa_monthly_report' => 'Μηνιαία αναφορά',
	'qa_companies' => 'Εταιρείες',
	'qa_company_name' => 'Όνομα Εταιρείας',
	'qa_address' => 'Διεύθυνση',
	'qa_website' => 'Ιστοσελίδα',
	'qa_contacts' => 'Επαφές',
	'qa_company' => 'Εταιρεία',
	'qa_first_name' => 'Όνομα',
	'qa_last_name' => 'Επώνυμο',
	'qa_phone' => 'Τηλέφωνο',
	'qa_phone1' => 'Τηλ 1',
	'qa_phone2' => 'Τηλ 2',
	'qa_photo' => 'Φωτογραφία (μέγιστο 8mb)',
	'qa_category_name' => 'Όνομα κατηγορίας',
	'qa_products' => 'Προϊόντα',
	'qa_product_name' => 'Όνομα προϊόντος',
	'qa_price' => 'Τιμή',
	'qa_tags' => 'Ετικέτες',
	'qa_tag' => 'Ετικέτα',
	'qa_photo1' => 'Φωτογραφία1',
	'qa_photo2' => 'Φωτογραφία2',
	'qa_photo3' => 'Φωτογραφία3',
	'qa_calendar' => 'Ημερολόγιο',
	'qa_statuses' => 'Καταστάσεις',
	'qa_task_management' => 'Διαχείριση εργασιών',
	'qa_tasks' => 'Εργασίες',
	'qa_status' => 'Κατάσταση',
	'qa_attachment' => 'Επισύναψη',
	'qa_assets' => 'Περουσιακά στοιχεία',
	'qa_asset' => 'Περουσιακό στοιχείο',
	'qa_serial_number' => 'Σειριακός αριθμός',
	'qa_location' => 'Τοποθεσία',
	'qa_locations' => 'Τοποθεσίες',
	'qa_notes' => 'Σημειώσεις',
	'qa_assets_history' => 'Ιστορία στοιχείων',
	'qa_assets_management' => 'Διαχείριση στοιχείων',
	'qa_content_management' => 'Διαχείριση περιεχομένου',
	'qa_text' => 'Κείμενο',
	'qa_featured_image' => 'Εικόνα εμφάνισης',
	'qa_pages' => 'Σελίδες',
	'qa_axis' => 'Άξονας',
	'qa_show' => 'Εμφάνιση',
	'qa_group_by' => 'Ομαδοποίηση κατά',
	'qa_chart_type' => 'Τύπος Γραφήματος',
	'qa_create_new_report' => 'Δημιουργία νέας αναφοράς',
	'qa_created_at' => 'Δημιουργήθηκε στις',
	'qa_updated_at' => 'Ενημερώθηκε στις',
	'qa_deleted_at' => 'Διαγράφηκε στις',
	'qa_reports_x_axis_field' => 'Άξονας Χ',
	'qa_reports_y_axis_field' => 'Άξονας Ψ',
	'qa_select_crud_placeholder' => 'Παρακαλώ επιλέξτε ένα από τα CRUDs',
	'qa_currency' => 'Νόμισμα',
	'qa_current_password' => 'Τρέχων κωδικός',
	'qa_new_password' => 'Νέος κωδικός',
	'qa_dashboard_text' => 'Είσαι μέσα!',
	'qa_forgot_password' => 'Ξέχασες τον κωδικό σου;',
	'qa_remember_me' => 'Θυμήσου με',
	'qa_login' => 'Είσοδος',
	'qa_change_password' => 'Αλλαγή κωδικού',
	'qa_csv' => 'CSV',
	'qa_print' => 'Εκτύπωση',
	'qa_excel' => 'Excel',
	'qa_copy' => 'Αντιγραφή',
	'qa_reset_password' => 'Καθαρισμός κωδικού',
	'qa_email_greet' => 'Γεια',
	'qa_email_regards' => 'Ευχαριστίες',
	'qa_confirm_password' => 'Επιβεβαίωση κωδικού',
	'qa_if_you_are_having_trouble' => 'Αν έχετε πρόβλημα πατώντας το',
	'qa_please_select' => 'Παρακαλώ επιλέξτε',
	'qa_register' => 'Εγγραφείτε',
	'qa_registration' => 'Εγγραφή',
	'qa_not_approved_title' => 'Δεν είστε αποδεκτός',
	'qa_there_were_problems_with_input' => 'Υπήρξαν προβλήματα με την εισαγωγή',
	'qa_whoops' => 'Ουπς!',
	'qa_file_contains_header_row' => 'Το αρχείο περιέχει επικεφαλίδα;',
	'qa_csvImport' => 'Εισαγωγή από CSV ',
	'qa_csv_file_to_import' => 'Αρχείο CSV για εισαγωγή',
	'qa_parse_csv' => 'Ανάλυση CSV',
	'qa_import_data' => 'Εισαγωγή δεδομένων',
	'qa_subscription-billing' => 'Συνδρομές',
	'qa_subscription-payments' => 'Πληρωμές',
	'qa_basic_crm' => 'Βασικό CRM',
	'qa_customers' => 'Πελάτες',
	'qa_customer' => 'Πελάτης',
	'qa_select_all' => 'Επιλογή όλων',
	'qa_deselect_all' => 'Αποεπιλογή όλων',
	'qa_coupon_management' => 'Διαχείριση Κουπονιών',
	'qa_projects' => 'Έργα',
	'qa_project' => 'Έργο',
	'qa_expense_category' => 'Κατηγορία Εξόδων',
	'qa_expense_categories' => 'Κατηγορίες Εξόδων',
	'qa_expense_management' => 'Διαχείριση Εξόδων',
	'qa_entry_date' => 'Ημερομηνία Καταχώρησης',
	'qa_contact_management' => 'Διαχείριση Επαφών',
	'qa_skype' => 'Skype',
	'qa_product_management' => 'Διαχείριση Προϊόντων',
	'qa_assigned_to' => 'Ανατέθηκε',
	'qa_excerpt' => 'Απόσπασμα',
	'qa_notifications' => 'Ειδοποιήσεις',
	'qa_stripe_transactions' => 'Συναλλαγές Stripe',
	'qa_upgrade_to_premium' => 'Αναβάθμιση σε Premium',
	'qa_messages' => 'Μηνύματα',
	'qa_you_have_no_messages' => 'Δεν έχετε μηνύματα.',
	'qa_all_messages' => 'Όλα τα Μηνύματα',
	'qa_new_message' => 'Νέο Μήνυμα',
	'qa_outbox' => 'Εξερχόμενα',
	'qa_inbox' => 'Εισερχόμενα',
	'qa_recipient' => 'Παραλήπτης',
	'qa_subject' => 'Θέμα',
	'qa_message' => 'Μήνυμα',
	'qa_send' => 'Αποστολή',
	'qa_reply' => 'Απάντηση',
	'qa_prefix' => 'Πρόθεμα',
	'qa_suffix' => 'Επίθεμα',
	'qa_client_management' => 'Διαχείριση Πελατών',
	'qa_country' => 'Χώρα',
	'qa_client_status' => 'Κατάσταση Πελατών',
	'qa_clients' => 'Πελάτες',
	'qa_client_statuses' => 'Καταστάσεις Πελατών',
	'qa_currencies' => 'Ισοτιμίες',
	'qa_main_currency' => 'Κύριο Νόμισμα',
	'qa_documents' => 'Έγγραφα',
	'qa_file' => 'Αρχείο',
	'qa_income_source' => 'Πηγή Εσόδων',
	'qa_income_sources' => 'Πηγές Εσόδων',
	'qa_note_text' => 'Κείμενο Σημείωσης',
	'qa_client' => 'Πελάτης',
	'qa_start_date' => 'Ημερομηνία Έναρξης',
	'qa_budget' => 'Προϋπολογισμός',
	'qa_project_status' => 'Κατάσταση Έργου',
	'qa_project_statuses' => 'Καταστάσεις Έργων',
	'qa_transactions' => 'Συναλλαγές',
	'qa_transaction_types' => 'Τύποι Συναλλαγών',
	'qa_transaction_type' => 'Τύπος Συναλλαγών',
	'qa_transaction_date' => 'Ημερομηνία Συναλλαγής',
	'qa_password_confirm' => 'Επιβεβαίωση νέου κωδικού',
	'qa_colvis' => 'Εμφάνιση Στηλών',
	'qa_pdf' => 'PDF',
	'qa_team-management' => 'Ομάδες',
	'qa_team-management-singular' => 'Ομάδα',
	'qa_faq_management' => 'Διαχείριση FAQ',
	'qa_time' => 'Ώρα',
	'qa_campaign' => 'Καμπάνια',
	'qa_campaigns' => 'Καμπάνιες',
	'qa_discount_amount' => 'Ποσό Έκπτωσης',
	'qa_discount_percent' => 'Ποσοστό Έκπτωσης',
	'qa_coupons_amount' => 'Ποσό Κουπονιών',
	'qa_time_management' => 'Διαχείριση Χρόνου',
	'qa_work_types' => 'Τύποι εργασιών',
	'qa_notify_user' => 'Ειδοποίηση Χρήστη',
	'qa_create_new_notification' => 'Δημιουργία Νέας Ειδοποίησης',
	'qa_imported_rows_to_table' => 'Έγινε εισαγωγή :rows εγγραφών στον πίνακα :table',
	'qa_calendar_sources' => 'Πηγές Ημερολογίου',
	'qa_new_calendar_source' => 'Δημιουργία νέας πηγής ημερολογίου',
	'qa_crud_title' => 'Τίτλος Διαχειριστικής Σελίδας',
	'qa_create_new_calendar_source' => 'Δημιουργία Νέας Πηγής Ημερολογίου',
	'qa_edit_calendar_source' => 'Επεξεργασία Πηγής Ημερολογίου',
	'qa_client_management_settings' => 'Ρυθμίσεις Διαχείρισης Πελατών',
	'qa_no_reports_yet' => 'Δεν υπάρχουν αναφορές ακόμη.',
	'qa_select_users_placeholder' => 'Παρακαλώ επιλέξτε έναν από τους Χρήστες',
	'qa_is_created' => 'δημιουργήθηκε',
	'qa_is_updated' => 'ενημερώθηκε',
	'qa_is_deleted' => 'διαγράφηκε',
	'qa_not_approved_p' => 'Ο λογαριασμός σας ακόμα δεν έχει εγκριθεί από τον διαχειριστή. Παρακαλώ, να έχετε υπομονή και δοκιμάστε αργότερα.',
	'qa_action_model' => 'Μοντέλο Ενέργειας',
	'qa_action_id' => 'Id Ενέργειας',
	'qa_time_entries' => 'Εισαγωγές Χρόνου',
	'qa_due_date' => 'Ημερομηνία που',
	'qa_assigned_user' => 'Ορισμένος (χρήστης)',
	'qa_select_dt_placeholder' => 'Παρακαλώ επιλέξτε ένα από τα πεδία Ημερομηνίας/Ώρας',
	'qa_integer_float_placeholder' => 'Παρακαλώ επιλέξτε ένα από τα πεδία Ακέραιου / Δεκαδικού',
	'qa_change_notifications_field_1_label' => 'Στείλε email ειδοποίησης στο Χρήστη',
	'qa_when_crud' => 'Όταν το CRUD',
	'qa_crud_date_field' => 'Crud πεδίο Ημερομηνίας',
	'qa_label_field' => 'Πεδίο Ετικέτας',
	'qa_crud_event_field' => 'Ετικέτα πεδίου γεγονότος ',
	'qa_fee_percent' => 'Ποσοστό άδειας',
	'quickadmin_title' => 'survey',
];