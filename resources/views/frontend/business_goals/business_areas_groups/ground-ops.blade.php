@extends('frontend.layouts.app')

@section('title', 'Ground Operations')

@section('content')
    <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <iframe src="{{ route('frontend.business_goals.single.quadrant') }}?business_area_id=2&embed=1" width="100%" style="min-height: 320px; border: none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <iframe src="{{ route('frontend.business_goals.single.quadrant') }}?business_area_id=3&embed=1" width="100%" style="min-height: 320px; border: none;"></iframe>
                        </div>
                    </div>
                </div>
            </div>
    </div>
@endsection

@push('after-scripts')
{{--    <script>--}}
{{--        document.addEventListener("DOMContentLoaded", () => {--}}
{{--            const iframes = document.querySelectorAll("iframe");--}}

{{--            iframes.forEach(iframe => {--}}
{{--                const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;--}}

{{--                if (!iframeDocument.documentElement) {--}}
{{--                    iframeDocument.appendChild(iframeDocument.createElement('html'));--}}
{{--                }--}}
{{--                if (!iframeDocument.head) {--}}
{{--                    iframeDocument.documentElement.appendChild(iframeDocument.createElement('head'));--}}
{{--                }--}}
{{--                if (!iframeDocument.body) {--}}
{{--                    iframeDocument.documentElement.appendChild(iframeDocument.createElement('body'));--}}
{{--                }--}}

{{--                Array.from(document.styleSheets).forEach(styleSheet => {--}}
{{--                    if (styleSheet.href) {--}}
{{--                        const link = iframeDocument.createElement("link");--}}
{{--                        link.rel = "stylesheet";--}}
{{--                        link.href = styleSheet.href;--}}
{{--                        iframeDocument.head.appendChild(link);--}}
{{--                    } else if (styleSheet.ownerNode && styleSheet.ownerNode.nodeName === "STYLE") {--}}
{{--                        const style = iframeDocument.createElement("style");--}}
{{--                        style.textContent = styleSheet.ownerNode.textContent;--}}
{{--                        iframeDocument.head.appendChild(style);--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}

{{--    </script>--}}
@endpush
