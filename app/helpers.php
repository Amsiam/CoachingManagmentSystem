<?php

if (!function_exists('getPoint')) {
    function getPoint($subject, $student)
    {
        $cq = $subject->marks
            ->where('student_id', $student->id)
            ->first()->cq;
        $practical = $subject->marks
            ->where('student_id', $student->id)
            ->first()->practical;
        $mcq = $subject->marks
            ->where('student_id', $student->id)
            ->first()->mcq;

        $gradeMarkCQ = $subject->cq_mark;
        $gradeMarkMCQ = $subject->mcq_mark;
        $gradeMarkPractical = $subject->practical_mark;

        if ($cq < floor($gradeMarkCQ * .33)) {
            return 0;
        }
        if ($mcq < floor($gradeMarkMCQ * .33)) {
            return 0;
        }
        if ($practical < floor($gradeMarkPractical * .33)) {
            return 0;
        }

        $total = $cq + $mcq + $practical;

        $totalGrade = $gradeMarkCQ + $gradeMarkMCQ + $gradeMarkPractical;

        if ($subject->has2ndPart) {
            $cq = $subject->has2ndPart->marks
                ->where('student_id', $student->id)
                ->first()->cq;
            $practical = $subject->has2ndPart->marks
                ->where('student_id', $student->id)
                ->first()->practical;
            $mcq = $subject->has2ndPart->marks
                ->where('student_id', $student->id)
                ->first()->mcq;

            $gradeMarkCQ = $subject->has2ndPart->cq_mark;
            $gradeMarkMCQ = $subject->has2ndPart->mcq_mark;
            $gradeMarkPractical = $subject->has2ndPart->practical_mark;

            if ($cq < floor($gradeMarkCQ * .33)) {
                return 0;
            }
            if ($mcq < floor($gradeMarkMCQ * .33)) {
                return 0;
            }
            if ($practical < floor($gradeMarkPractical * .33)) {
                return 0;
            }

            $total += $cq + $mcq + $practical;

            $totalGrade += $gradeMarkCQ + $gradeMarkMCQ + $gradeMarkPractical;
        }

        if ($total + 1 < floor($totalGrade * .40)) {
            return 1;
        }

        if ($total + 1 < floor($totalGrade * .50)) {
            return 2;
        }

        if ($total + 1 < floor($totalGrade * .60)) {
            return 3;
        }

        if ($total + 1 < floor($totalGrade * .70)) {
            return 3.5;
        }

        if ($total + 1 < floor($totalGrade * .80)) {
            return 4;
        }
        return 5;
    }
}


if (!function_exists('getGrade')) {
    function getGrade($point)
    {

        if ($point >= 5)
            return "A+";

        if ($point >= 4)
            return "A";

        if ($point >= 3.5)
            return "A-";

        if ($point >= 3)
            return "B";

        if ($point >= 2)
            return "C";
        if ($point >= 1)
            return "D";

        return "F";
    }
}
