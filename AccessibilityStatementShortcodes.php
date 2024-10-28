<?php
class AccessibilityStatementShortcodes {

    /**
     * Start up
     */
    public function __construct() {
        $this->add_my_shortcodes();
    }

    /**
     * Register shortcodes
     */
    public function add_my_shortcodes() {
        function blog_name_shortcode() { 
            $blog_name = get_bloginfo('name');
            $message = '<strong><span id="a11y-institution">'.$blog_name.'</span></strong>';
            return $message;
        } 
        add_shortcode('blog_name', 'blog_name_shortcode'); 

        function blog_link_shortcode() { 
            $blog_name = get_bloginfo('name');
            $blog_url = get_bloginfo('url');
            $message = '<span><a href="'.$blog_url.'" target="_blank">'.$blog_name.' tīmekļvietne</a></span>'; 
            return $message;
        } 
        add_shortcode('blog_link', 'blog_link_shortcode'); 
        
        function blog_name_and_link_shortcode() {
            $blog_name = get_bloginfo('name');
            $blog_url = get_bloginfo('url');

            $message = '<span id="a11y-web-name">'.$blog_name.' tīmekļvietni</span> - <span id="a11y-web-link"><a href="'.$blog_url.'" target="_blank">'.$blog_url.'</a></span>'; 
            return $message;
        } 
        add_shortcode('blog_name_and_link', 'blog_name_and_link_shortcode'); 
        
        function evaluation_date_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yEvaluationDate = get_post_meta( $post_id, 'accessibilityStatement_a11yEvaluationDate', true );
            if ($a11yEvaluationDate) {
                $a11yEvaluationDate = date('d.m.Y', strtotime($a11yEvaluationDate));
            }

            $message = '<span id="a11y-evaluation-date">'.$a11yEvaluationDate.'</span>'; 
            return $message;
        } 
        add_shortcode('evaluation_date', 'evaluation_date_shortcode'); 
        
        function a11y_author_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yAuthor = get_post_meta( $post_id, 'accessibilityStatement_a11yAuthor', true );
            $message = '<span id="a11y-author">'.$a11yAuthor.'</span>'; 
            return $message;
        } 
        add_shortcode('a11y_author', 'a11y_author_shortcode'); 


        
        function a11y_Evaluation_Fits_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yEvaluationFits = get_post_meta( $post_id, 'accessibilityStatement_a11yEvaluationFits', true );

            $message = ''; 
            if ($a11yEvaluationFits == 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445') {
                $message = 'daļēji atbilst noteikumiem Nr. 445 turpmāk uzskaitīto iemeslu dēļ.';
            }elseif ($a11yEvaluationFits == 'tīmekļvietne pilnīgi atbilst MK noteikumiem Nr. 445') {
                $message = ' pilnīgi atbilst MK noteikumiem Nr. 445';
            }
            return $message;
        } 
        add_shortcode('a11y_Evaluation_Fits', 'a11y_Evaluation_Fits_shortcode');      




        
        function a11y_Non_Compliance_List_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yEvaluationFits = get_post_meta( $post_id, 'accessibilityStatement_a11yEvaluationFits', true );
            $a11yNonComplianceList =  get_post_meta( $post_id, 'accessibilityStatement_a11yNonComplianceList', true );
            $a11yNonComplianceList =  apply_filters( 'the_content', $a11yNonComplianceList );
            $message = ''; 
            if ($a11yEvaluationFits == 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445') {
                $message = '<h4>Neatbilstība prasībām, kas minētas noteikumos Nr. 445:</h4> <div id="a11y-non-compliance-list">' . $a11yNonComplianceList. '</div>';
            }
            return $message;
        } 
        add_shortcode('a11y_Non_Compliance_List', 'a11y_Non_Compliance_List_shortcode');         



        function what_Are_Alternatives_To_Non_Accessable_Content_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yEvaluationFits = get_post_meta( $post_id, 'accessibilityStatement_a11yEvaluationFits', true );
            $whatAreAlternativesToNonAccessableContent =  get_post_meta( $post_id, 'accessibilityStatement_whatAreAlternativesToNonAccessableContent', true );
            $whatAreAlternativesToNonAccessableContent =  apply_filters( 'the_content', $whatAreAlternativesToNonAccessableContent );
            $message = ''; 
            if ($a11yEvaluationFits == 'tīmekļvietne daļēji atbilst MK noteikumiem Nr. 445') {
                $message = '<h3>Piekļūstamības alternatīvas</h3>' . $whatAreAlternativesToNonAccessableContent;
            }
            return $message;
        } 
        add_shortcode('what_Are_Alternatives_To_Non_Accessable_Content', 'what_Are_Alternatives_To_Non_Accessable_Content_shortcode'); 

        function contact_mail_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yMail = get_post_meta( $post_id, 'accessibilityStatement_a11yMail', true );
            $message = '<a id="a11y-mail" href="mailto:'.$a11yMail.'">'.$a11yMail.'</a>'; 
            return $message;
        } 
        add_shortcode('contact_mail', 'contact_mail_shortcode'); 
        
        function contact_phone_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yPhone = get_post_meta( $post_id, 'accessibilityStatement_a11yPhone', true ); // TODO remove spaces

            $message = '<a id="a11y-phone" href="tel:'.$a11yPhone.'">'.$a11yPhone.'.</a>'; 
            return $message;
        } 
        add_shortcode('contact_phone', 'contact_phone_shortcode'); 
        
        function other_contacts_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yOtherContacts = get_post_meta( $post_id, 'accessibilityStatement_a11yOtherContacts', true ); 

            $message = '<span id="a11y-other-contacts">'.$a11yOtherContacts.'</span>'; 
            return $message;
        } 
        add_shortcode('other_contacts', 'other_contacts_shortcode'); 
        
        function response_time_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yTime = get_post_meta( $post_id, 'accessibilityStatement_a11yTime', true ); 

            $message = '<span id="a11y-time">'.$a11yTime.'</span>'; 
            return $message;
        } 
        add_shortcode('response_time', 'response_time_shortcode'); 
        
        function complaints_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yComplaints =         get_post_meta( $post_id, 'accessibilityStatement_a11yComplaints', true ); 
 

            $message = '<span id="a11y-complaints">'.$a11yComplaints.' </span>'; 
            return $message;
        } 
        add_shortcode('complaints', 'complaints_shortcode'); 
        
        function first_notify_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yFirstNotify = get_post_meta( $post_id, 'accessibilityStatement_a11yFirstNotify', true ); 
            if ($a11yFirstNotify) {
                $a11yFirstNotify = date('d.m.Y', strtotime($a11yFirstNotify));
            }

            $message = '<span id="a11y-first-notify">'.$a11yFirstNotify.'</span>'; 
            return $message;
        } 
        add_shortcode('first_notify', 'first_notify_shortcode'); 

        function approved_by_name_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yApprovedBy = get_post_meta( $post_id, 'accessibilityStatement_a11yApprovedBy', true );


            $message = '<span id="a11y-approved-by">'.$a11yApprovedBy.'</span>'; 
            return $message;
        } 
        add_shortcode('approved_by_name', 'approved_by_name_shortcode'); 
        
        function approved_by_job_title_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yApprovedByJobtitle = get_post_meta( $post_id, 'accessibilityStatement_a11yApprovedByJobtitle', true );

            $message = '<span id="a11y-approved-by-jobtitle">'.$a11yApprovedByJobtitle.'</span>'; 
            return $message;
        } 
        add_shortcode('approved_by_job_title', 'approved_by_job_title_shortcode'); 
        
        function has_statement_been_rereviewed_shortcode() {
            $post_id = get_option( 'a11yStatement_post_id' );
            $HasStatementBeenReReviewed = get_post_meta( $post_id, 'accessibilityStatement_HasStatementBeenReReviewed', true ); 
            $a11yRepeatedNotify = get_post_meta( $post_id, 'accessibilityStatement_a11yRepeatedNotify', true );
            if ($a11yRepeatedNotify) {
                $a11yRepeatedNotify = date('d.m.Y', strtotime($a11yRepeatedNotify));
            }
            $message = '';
            
            if ($HasStatementBeenReReviewed) {
                $message = '<p>Atkārtoti pārskatīts <span id="a11y-first-notify">'.$a11yRepeatedNotify.'</span>.</p>'; 
            }

            return $message;
        } 
        add_shortcode('has_statement_been_rereviewed', 'has_statement_been_rereviewed_shortcode'); 
        
        function accept_document_shortcode() {
            $message = '';
            $post_id = get_option( 'a11yStatement_post_id' );
            $a11yAcceptDocument =  get_post_meta( $post_id, 'accessibilityStatement_a11yAcceptDocument', true );
            $file_name  = get_the_title($a11yAcceptDocument);
            $file_url  = wp_get_attachment_url($a11yAcceptDocument);

            if ($file_name && $file_url) {
                $message = '<a href="'.$file_url.'" id="a11y-accept-document">'.$file_name.'</a>'; 
            }
                
            return $message;
        } 
        add_shortcode('accept_document', 'accept_document_shortcode'); 

    }



}