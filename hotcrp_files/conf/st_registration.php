<?php
// conf/st_registration.php -- local `registration:` search keyword
//
// Matches on whether the submission round's *registration* deadline
// (`sub_reg`, "Submission registration deadline") is still open for a given
// submission. Registered from conf/options.php via $Opt["searchKeywords"].
//
// Primary use: as a submission-field `edit_condition`, so that fields such as
// title/abstract/authors freeze at the registration deadline while the rest of
// the form stays editable until the submission deadline.

class Registration_SearchTerm extends SearchTerm {
    /** @var bool */
    private $open;

    /** @param bool $open */
    function __construct($open) {
        parent::__construct("registration");
        $this->open = $open;
    }
    static function parse($word, SearchWord $sword, PaperSearch $srch) {
        if (strcasecmp($word, "open") === 0) {
            return new Registration_SearchTerm(true);
        } else if (strcasecmp($word, "closed") === 0) {
            return new Registration_SearchTerm(false);
        }
        $srch->lwarning($sword, "<0>Only “registration:open” and “registration:closed” are allowed");
        return new False_SearchTerm;
    }
    function sqlexpr(SearchQueryInfo $sqi) {
        return "true";
    }
    function test(PaperInfo $row, $xinfo) {
        // Placeholder rows have negative IDs and no submission round; treat
        // registration as open so settings validation sees a live field.
        if ($row->paperId < 0) {
            return $this->open;
        }
        return $row->submission_round()->time_register(true) === $this->open;
    }
    function about() {
        return self::ABOUT_SUB;
    }
}
