<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add indexes for questionnaires table
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->index('survey_id', 'idx_questionnaires_survey_id');
            $table->index(['survey_id', 'created_at'], 'idx_questionnaires_survey_created');
        });

        // Add indexes for items table
        Schema::table('items', function (Blueprint $table) {
            $table->index('survey_id', 'idx_items_survey_id');
            $table->index(['survey_id', 'order'], 'idx_items_survey_order');
            $table->index('question_id', 'idx_items_question_id');
        });

        // Add indexes for responses table
        Schema::table('responses', function (Blueprint $table) {
            $table->index('questionnaire_id', 'idx_responses_questionnaire_id');
            $table->index('question_id', 'idx_responses_question_id');
            $table->index(['questionnaire_id', 'question_id'], 'idx_responses_questionnaire_question');
            $table->index(['question_id', 'answer_id'], 'idx_responses_question_answer');
        });

        // Add indexes for loguseragents table
        Schema::table('loguseragents', function (Blueprint $table) {
            $table->index('item_id', 'idx_loguseragents_item_id');
            $table->index('ipv6', 'idx_loguseragents_ipv6');
            // Split the composite index due to MySQL key length limits
            $table->index(['ipv6', 'os'], 'idx_loguseragents_ip_os');
            $table->index(['browser', 'browser_version'], 'idx_loguseragents_browser');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes from questionnaires table
        Schema::table('questionnaires', function (Blueprint $table) {
            $table->dropIndex('idx_questionnaires_survey_id');
            $table->dropIndex('idx_questionnaires_survey_created');
        });

        // Remove indexes from items table
        Schema::table('items', function (Blueprint $table) {
            $table->dropIndex('idx_items_survey_id');
            $table->dropIndex('idx_items_survey_order');
            $table->dropIndex('idx_items_question_id');
        });

        // Remove indexes from responses table
        Schema::table('responses', function (Blueprint $table) {
            $table->dropIndex('idx_responses_questionnaire_id');
            $table->dropIndex('idx_responses_question_id');
            $table->dropIndex('idx_responses_questionnaire_question');
            $table->dropIndex('idx_responses_question_answer');
        });

        // Remove indexes from loguseragents table
        Schema::table('loguseragents', function (Blueprint $table) {
            $table->dropIndex('idx_loguseragents_item_id');
            $table->dropIndex('idx_loguseragents_ipv6');
            $table->dropIndex('idx_loguseragents_ip_os');
            $table->dropIndex('idx_loguseragents_browser');
        });
    }
};
