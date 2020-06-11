# Google Analytics

> How we setup Google Analytics (GA)

- GA should only be fired from [Google Tag Manager](./tracking/analytics-google-tag-manager.md) based on the cookies set by the [GDPR](./gdpr.md) script 
- The default setting should have the IP address of the user annonimized until they have accepted the GDPR terms.

## GA setup within Google Tag Manager
Make sure you have set up the User-Defined Variables and Triggers as per the instructions in the [Google Tag Manager](./tracking/analytics-google-tag-manager.md) documentation.

Now you need to set up the following tags

| Name | Type | Firing Triggers |
|----------|-------------|----------|
| GDPR - Accept - GA Tracking | Custom HTML | GDPR - Accepted |
| GDPR - Decline - GA Tracking | Custom HTML | GDPR - Declined |
| GDPR - Undefined - GA Tracking | Custom HTML | GDPR - Undefined |

The custom HTML you'll find below for each trigger, make sure you update the UA ID which should be provided by the producer.

### GDPR - Accept - GA Tracking
```
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXXX-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-XXXXXXXX-1', { 'forceSSL': true });
</script>
```

### GDPR - Decline - GA Tracking & GDPR - Undefined - GA Tracking
```
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-XXXXXXXX-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-XXXXXXXX-1', { 'anonymize_ip': true, 'forceSSL': true });
</script>
```

Double check you have set ```'anonymize_ip': true``` in both GDPR - Decline - GA Tracking & GDPR - Undefined - GA Tracking

