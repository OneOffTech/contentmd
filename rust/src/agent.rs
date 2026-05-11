use std::env;

/// All environment variables whose presence signals a known agent runtime.
const AGENT_VARS: &[&str] = &[
    "AI_AGENT",
    "CURSOR_AGENT",
    "GEMINI_CLI",
    "CODEX_SANDBOX",
    "CODEX_CI",
    "CODEX_THREAD_ID",
    "AUGMENT_AGENT",
    "AMP_CURRENT_THREAD_ID",
    "OPENCODE_CLIENT",
    "OPENCODE",
    "CLAUDECODE",
    "CLAUDE_CODE",
    "CLAUDE_CODE_IS_COWORK",
    "REPL_ID",
    "ANTIGRAVITY_AGENT",
    "PI_CODING_AGENT",
    "KIRO_AGENT_PATH",
    "COPILOT_MODEL",
    "COPILOT_ALLOW_ALL",
    "COPILOT_GITHUB_TOKEN",
    "COPILOT_CLI",
];

/// Returns the name of the first env-var (or sentinel string) that indicates an
/// agent runtime, or `None` when running in a regular terminal.
pub(crate) fn detected_agent() -> Option<&'static str> {
    for &var in AGENT_VARS {
        if env::var(var).is_ok() {
            return Some(var);
        }
    }
    None
}

/// Returns `true` when an agent runtime is detected via environment heuristics.
pub fn is_agent_env() -> bool {
    detected_agent().is_some()
}

/// Returns `true` when the `--agent` flag was passed explicitly **or** when an
/// agent runtime is auto-detected from the environment.
pub fn effective(explicit: bool) -> bool {
    explicit || is_agent_env()
}

#[cfg(test)]
mod tests {
    use super::*;

    #[test]
    fn agent_vars_list_is_non_empty() {
        assert!(!AGENT_VARS.is_empty());
    }

    #[test]
    fn agent_vars_contains_known_entries() {
        assert!(AGENT_VARS.contains(&"CLAUDECODE"));
        assert!(AGENT_VARS.contains(&"CURSOR_AGENT"));
        assert!(AGENT_VARS.contains(&"COPILOT_MODEL"));
        assert!(AGENT_VARS.contains(&"AI_AGENT"));
    }

    #[test]
    fn effective_true_when_explicit() {
        // Force explicit=true regardless of environment.
        assert!(effective(true));
    }
}
