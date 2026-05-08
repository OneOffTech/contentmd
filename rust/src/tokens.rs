/// Estimates token count using ~4 chars per token (English approximation).
pub fn estimate(text: &str) -> usize {
    (text.len() + 3) / 4
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn estimate_empty_is_zero() {
        assert_eq!(estimate(""), 0);
    }

    #[test]
    fn estimate_four_chars_is_one_token() {
        assert_eq!(estimate("test"), 1);
    }

    #[test]
    fn estimate_five_chars_rounds_up() {
        assert_eq!(estimate("hello"), 2);
    }

    #[test]
    fn estimate_exact_multiple() {
        assert_eq!(estimate(&"a".repeat(100)), 25);
    }

    #[test]
    fn estimate_grows_with_length() {
        assert!(estimate("longer text") > estimate("short"));
    }
}
