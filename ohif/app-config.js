window.config = {
  routerBasename: '/',
  showStudyList: true,
  servers: {
    dicomWeb: [
      {
        name: 'Orthanc',
        wadoUriRoot: 'http://host.docker.internal:8042/wado',
        qidoRoot: 'http://host.docker.internal:8042/dicom-web',
        wadoRoot: 'http://host.docker.internal:8042/dicom-web',
        qidoSupportsIncludeField: true,
        imageRendering: 'wadors',
        thumbnailRendering: 'wadors',
        enableStudyLazyLoad: true,
        supportsFuzzyMatching: true,
      },
    ],
  },
  whiteLabeling: {
    /* Used to configure the banner on OHIF's default index page */
    createReportButtonComponent: ['reports-file'],
    fullServiceDetails: {
      enabled: true,
      title: 'TeleRadiology System',
      logoUrl: '/assets/ohif-logo.png',
    },
    showWarningMessageBanner: false,
  },
  investigationalUseDialog: {
    option: 'never',
  },
  showWarningMessageForCrossOrigin: false,
  maxConcurrentMetadataRequests: 10,
  minConcurrentMetadataRequests: 1,
}; 