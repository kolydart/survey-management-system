# Analysis: The Root Cause of the Form Validation Error

## Overview

This document explains the root cause of the "An invalid form control is not focusable" error that occurred when users attempted to submit the survey form with questions controlled by multiple JavaScript visibility dependencies.

## The Problem

Question **2.7** (ID: `3574`, field: `3574_content_16`) contains a textarea field that is controlled by **THREE different JavaScript scripts**:

1. **toggleTextarea.blade.php**: Shows/hides the textarea when radio option 16 ("Κάτι άλλο" / "Something else") is selected
2. **Question dependency from 1.1**: Hides question 2.7 when user selects "Καθόλου" (Never) in question 1.1
3. **Question dependency from 2.1**: Shows question 2.7 when user checks "Finale" in question 2.1 (with `invertLogic: true`)

## The Issue in Detail

### JavaScript Dependency Rules

Question **2.7** (class `q_3574`) has **TWO dependency rules** that control its visibility:

#### 1. From Question 1.1 (3416) - Normal logic:
```javascript
{
    questionTriggerClass: '3416',
    answerTriggerId: '3416_1_select',  // "Καθόλου" (Never)
    questionAlterClass: '3574',
    hideWhenEmpty: false
}
```
- **HIDES** question 2.7 when user selects "Never" in question 1.1

#### 2. From Question 2.1 (3570) - Inverted logic:
```javascript
{
    questionTriggerClass: '3570',
    answerTriggerId: '3570_146_select',  // "Finale" checkbox
    questionAlterClass: '3574',
    invertLogic: true
}
```
- **SHOWS** question 2.7 when "Finale" is checked
- **HIDES** question 2.7 when "Finale" is NOT checked

### The Problem Scenario

1. User answers question 1.1 with anything OTHER than "Never" → Question **2.7** is **visible**
2. User checks "Finale" in question 2.1 → Question **2.7** remains **visible** (invertLogic keeps it shown)
3. User selects radio option "Κάτι άλλο" (Something else, value 16) in question **2.7**
4. The `toggleTextarea` script shows the textarea `3574_content_16` and adds `required="required"`
5. User later **unchecks "Finale"** in question 2.1
6. The dependency script with `invertLogic: true` runs and:
   - Detects Finale is NOT checked → `shouldHide = true`
   - Hides entire question 2.7
   - Removes `required` from all fields including the textarea
   - Disables all fields including the textarea
   - Clears all field values
7. **BUT** - the textarea has `required="required"` hardcoded in the HTML
8. The `toggleTextarea` script may run AFTER the dependency script due to event bubbling/timing
9. The textarea ends up in state: `required="required"` + `display: none` + possibly `disabled="disabled"`
10. Form submission fails with: **"An invalid form control with name='3574_content_16' is not focusable"**

## Root Causes

1. **Hardcoded `required="required"`** in the textarea HTML (`resources/views/partials/questionnaireRender.blade.php:164`)
2. **Multiple JavaScript controllers** (dependency script + toggleTextarea script) with no coordination
3. **Event timing issues** - toggleTextarea may re-enable `required` after dependency script disables it
4. **No parent visibility check** in toggleTextarea script

## Why This Happens

The issue is **conflicting JavaScript logic**:

- The **toggleTextarea** script manages the textarea visibility based on radio selection
- The **dependency** script manages the entire question visibility based on parent questions
- When question 2.7 is hidden by the dependency script, it properly disables and removes `required`, BUT:
  - If there's a race condition or script execution order issue
  - Or if the user interaction timing creates a state where `required` is re-added by toggleTextarea
  - The textarea can end up `required="required"` while `display: none` and `disabled`

Looking at the dependency script (Survey JavaScript, lines 6549-6573), when hiding:
```javascript
$field.removeAttr('required');
$field.prop('disabled', true);
```

But the **toggleTextarea** script can run AFTER the dependency script if the user changes the radio selection, and it will:
```javascript
.attr('required', true)
.attr('disabled', false)
.show(300);
```

This creates a race condition where the field can be `required` but the parent question is hidden.

## Solution

The fix involves two changes:

### 1. Remove hardcoded `required="required"` from textareas
File: `resources/views/partials/questionnaireRender.blade.php`

**Before:**
```blade
<textarea
    name="{{$item->question->id}}_content_{{$answer->id}}"
    id="{{$item->question->id}}_content_{{$answer->id}}"
    class="form-control"
    rows="5"
    placeholder=""
    required="required"
>{{old($item->question->id.'_content_'.$answer->id, '')}}</textarea>
```

**After:**
```blade
<textarea
    name="{{$item->question->id}}_content_{{$answer->id}}"
    id="{{$item->question->id}}_content_{{$answer->id}}"
    class="form-control"
    rows="5"
    placeholder=""
>{{old($item->question->id.'_content_'.$answer->id, '')}}</textarea>
```

### 2. Update toggleTextarea script to check parent question visibility
File: `resources/views/partials/js/toggleTextarea.blade.php`

**Before:**
```javascript
function check(){
    if ($('input#{{$item->question->id}}_{{$answer->id}}_select:checked').val() == {{$answer->id}}) {
        $('#{{$item->question->id}}_content_{{$answer->id}}')
            .attr('required', true)
            .attr('disabled', false)
            .show(300);
    } else {
        $('#{{$item->question->id}}_content_{{$answer->id}}')
            .val('')
            .attr('required', false)
            .removeAttr('required')
            .attr('disabled', true)
            .hide(300);
    }
};
```

**After:**
```javascript
function check(){
    var $parentQuestion = $('.q_{{$item->question->id}}');
    var $textarea = $('#{{$item->question->id}}_content_{{$answer->id}}');
    var isRadioSelected = $('input#{{$item->question->id}}_{{$answer->id}}_select:checked').val() == {{$answer->id}};
    var isParentVisible = $parentQuestion.is(':visible') && !$parentQuestion.find('input, textarea').first().prop('disabled');

    if (isRadioSelected && isParentVisible) {
        $textarea
            .attr('required', true)
            .attr('disabled', false)
            .show(300);
    } else {
        $textarea
            .val('')
            .attr('required', false)
            .removeAttr('required')
            .attr('disabled', true)
            .hide(300);
    }
};
```

## How the Solution Works

The enhanced `toggleTextarea` script now:

1. Checks if the parent question (`.q_{{$item->question->id}}`) is visible
2. Checks if the parent question's fields are not disabled (indicating it's active)
3. Only shows and requires the textarea when BOTH conditions are true:
   - The radio/checkbox option is selected
   - The parent question is visible and enabled

This prevents the textarea from becoming required when the parent question is hidden by dependency scripts, regardless of timing or event execution order.

## Result

**Before:**
- Textarea had hardcoded `required="required"`
- toggleTextarea script would add `required` even if parent was hidden
- Race conditions between dependency script and toggleTextarea
- Form validation error: "invalid form control is not focusable"
- Validation rule used `filled` which required all submitted `*_content*` fields to have values

**After:**
- No hardcoded `required` attribute on textareas
- toggleTextarea checks parent visibility before requiring the textarea
- If question 2.7 is hidden by either dependency (1.1 or 2.1), the textarea won't be made required
- Validation rule changed from `filled` to `nullable` for `*_content*` fields
- Form submission works correctly even when optional number/text fields are empty

The fix ensures proper coordination between the dependency scripts (controlled by Survey JavaScript) and the toggleTextarea scripts (controlled by Blade templates) for questions with multiple visibility controllers.

## Additional Fix: Validation Rule Change

Changed the validation rule in `StoreQuestionnaire.php` from:
```php
'*_content*' => 'filled|string|max:65535|regex:/^[^$^]*$/',
```

To:
```php
'*_content*' => 'nullable|string|max:65535|regex:/^[^$^]*$/',
```

This allows `*_content*` fields (from number, text, date inputs and textareas) to be submitted with empty values when they're optional or conditionally hidden by JavaScript, preventing the "Text fields are not allowed to be empty" validation error for fields that should be optional.
