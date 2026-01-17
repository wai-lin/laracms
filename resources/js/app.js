import Quill from "quill";
import "quill/dist/quill.snow.css";
import * as Sentry from "@sentry/browser";

window.Quill = Quill;

window.onload = function () {
    Sentry.init({
        dsn: "https://65cf5958a1be127c93b6374310e670a0@o4510726529810432.ingest.de.sentry.io/4510726531252304",
        // Setting this option to true will send default PII data to Sentry.
        // For example, automatic IP address collection on events
        sendDefaultPii: true,
        integrations: [
            Sentry.replayIntegration()
        ],
        // Session Replay
        replaysSessionSampleRate: 0.1, // This sets the sample rate at 10%. You may want to change it to 100% while in development and then sample at a lower rate in production.
        replaysOnErrorSampleRate: 1.0 // If you're not already sampling the entire session, change the sample rate to 100% when sampling sessions where errors occur.
    });
};