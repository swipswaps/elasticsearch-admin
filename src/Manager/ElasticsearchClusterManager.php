<?php

namespace App\Manager;

use App\Manager\AbstractAppManager;
use App\Manager\CallManager;
use App\Model\CallRequestModel;

class ElasticsearchClusterManager extends AbstractAppManager
{
    public function getClusterHealth()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setLog(false);
        $callRequest->setPath('/_cluster/health');
        $callResponse = $this->callManager->call($callRequest);
        return $callResponse->getContent();
    }

    public function getClusterStats(): array
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/stats');
        $callResponse = $this->callManager->call($callRequest);
        return $callResponse->getContent();
    }

    public function getClusterState(): array
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/state');
        $callResponse = $this->callManager->call($callRequest);
        return $callResponse->getContent();
    }

    public function getClusterSettings()
    {
        $callRequest = new CallRequestModel();
        $callRequest->setPath('/_cluster/settings');
        $callRequest->setQuery(['include_defaults' => 'true', 'flat_settings' => 'true']);
        $callResponse = $this->callManager->call($callRequest);
        $results = $callResponse->getContent();

        $clusterSettings = [];
        foreach ($results as $type => $rows) {
            foreach ($rows as $k => $v) {
                if (false == array_key_exists($k, $clusterSettings)) {
                    $clusterSettings[$k] = $v;
                }
            }
        }

        return $clusterSettings;
    }

    public function getClusterSettingsNotDynamic()
    {
        return [
            'bootstrap.ctrlhandler',
            'bootstrap.memory_lock',
            'bootstrap.system_call_filter',
            'cache.recycler.page.limit.heap',
            'cache.recycler.page.type',
            'cache.recycler.page.weight.bytes',
            'cache.recycler.page.weight.ints',
            'cache.recycler.page.weight.longs',
            'cache.recycler.page.weight.objects',
            'client.transport.ignore_cluster_name',
            'client.transport.nodes_sampler_interval',
            'client.transport.ping_timeout',
            'client.transport.sniff',
            'client.type',
            'cluster.election.back_off_time',
            'cluster.election.duration',
            'cluster.election.initial_timeout',
            'cluster.election.max_timeout',
            'cluster.election.strategy',
            'cluster.fault_detection.follower_check.interval',
            'cluster.fault_detection.follower_check.retry_count',
            'cluster.fault_detection.follower_check.timeout',
            'cluster.fault_detection.leader_check.interval',
            'cluster.fault_detection.leader_check.retry_count',
            'cluster.fault_detection.leader_check.timeout',
            'cluster.follower_lag.timeout',
            'cluster.indices.tombstones.size',
            'cluster.initial_master_nodes',
            'cluster.join.timeout',
            'cluster.name',
            'cluster.nodes.reconnect_interval',
            'cluster.publish.info_timeout',
            'cluster.publish.timeout',
            'cluster.remote.connect',
            'cluster.remote.connections_per_cluster',
            'cluster.remote.initial_connect_timeout',
            'cluster.remote.node.attr',
            'cluster.routing.allocation.type',
            'discovery.cluster_formation_warning_timeout',
            'discovery.find_peers_interval',
            'discovery.initial_state_timeout',
            'discovery.request_peers_timeout',
            'discovery.seed_hosts',
            'discovery.seed_providers',
            'discovery.seed_resolver.max_concurrent_resolvers',
            'discovery.seed_resolver.timeout',
            'discovery.type',
            'discovery.unconfigured_bootstrap_timeout',
            'discovery.zen.bwc_ping_timeout',
            'discovery.zen.fd.connect_on_network_disconnect',
            'discovery.zen.fd.ping_interval',
            'discovery.zen.fd.ping_retries',
            'discovery.zen.fd.ping_timeout',
            'discovery.zen.fd.register_connection_listener',
            'discovery.zen.hosts_provider',
            'discovery.zen.join_retry_attempts',
            'discovery.zen.join_retry_delay',
            'discovery.zen.join_timeout',
            'discovery.zen.master_election.ignore_non_master_pings',
            'discovery.zen.master_election.wait_for_joins_timeout',
            'discovery.zen.max_pings_from_another_master',
            'discovery.zen.ping.unicast.concurrent_connects',
            'discovery.zen.ping.unicast.hosts',
            'discovery.zen.ping.unicast.hosts.resolve_timeout',
            'discovery.zen.ping_timeout',
            'discovery.zen.publish.max_pending_cluster_states',
            'discovery.zen.send_leave_request',
            'discovery.zen.unsafe_rolling_upgrades_enabled',
            'enrich.cleanup_period',
            'enrich.coordinator_proxy.max_concurrent_requests',
            'enrich.coordinator_proxy.max_lookups_per_request',
            'enrich.coordinator_proxy.queue_capacity',
            'enrich.fetch_size',
            'enrich.max_concurrent_policy_executions',
            'enrich.max_force_merge_attempts',
            'gateway.auto_import_dangling_indices',
            'gateway.expected_data_nodes',
            'gateway.expected_master_nodes',
            'gateway.expected_nodes',
            'gateway.recover_after_data_nodes',
            'gateway.recover_after_master_nodes',
            'gateway.recover_after_nodes',
            'gateway.recover_after_time',
            'gateway.write_dangling_indices_info',
            'http.bind_host',
            'http.compression',
            'http.compression_level',
            'http.content_type.required',
            'http.cors.allow-credentials',
            'http.cors.allow-headers',
            'http.cors.allow-methods',
            'http.cors.allow-origin',
            'http.cors.enabled',
            'http.cors.max-age',
            'http.detailed_errors.enabled',
            'http.host',
            'http.max_chunk_size',
            'http.max_content_length',
            'http.max_header_size',
            'http.max_initial_line_length',
            'http.max_warning_header_count',
            'http.max_warning_header_size',
            'http.netty.max_composite_buffer_components',
            'http.netty.receive_predictor_size',
            'http.netty.worker_count',
            'http.pipelining.max_events',
            'http.port',
            'http.publish_host',
            'http.publish_port',
            'http.read_timeout',
            'http.reset_cookies',
            'http.tcp.keep_alive',
            'http.tcp.keep_count',
            'http.tcp.keep_idle',
            'http.tcp.keep_interval',
            'http.tcp.no_delay',
            'http.tcp.receive_buffer_size',
            'http.tcp.reuse_address',
            'http.tcp.send_buffer_size',
            'http.tcp_no_delay',
            'http.type',
            'http.type.default',
            'index.codec',
            'index.store.fs.fs_lock',
            'index.store.preload',
            'index.store.type',
            'indices.analysis.hunspell.dictionary.ignore_case',
            'indices.analysis.hunspell.dictionary.lazy',
            'indices.breaker.fielddata.type',
            'indices.breaker.request.type',
            'indices.breaker.total.use_real_memory',
            'indices.breaker.type',
            'indices.cache.cleanup_interval',
            'indices.fielddata.cache.size',
            'indices.lifecycle.history_index_enabled',
            'indices.memory.index_buffer_size',
            'indices.memory.interval',
            'indices.memory.max_index_buffer_size',
            'indices.memory.min_index_buffer_size',
            'indices.memory.shard_inactive_time',
            'indices.queries.cache.all_segments',
            'indices.queries.cache.count',
            'indices.queries.cache.size',
            'indices.query.bool.max_clause_count',
            'indices.query.query_string.allowLeadingWildcard',
            'indices.query.query_string.analyze_wildcard',
            'indices.requests.cache.expire',
            'indices.requests.cache.size',
            'indices.store.delete.shard.timeout',
            'ingest.geoip.cache_size',
            'ingest.grok.watchdog.interval',
            'ingest.grok.watchdog.max_execution_time',
            'ingest.user_agent.cache_size',
            'logger.level',
            'monitor.fs.refresh_interval',
            'monitor.jvm.gc.enabled',
            'monitor.jvm.gc.overhead.debug',
            'monitor.jvm.gc.overhead.info',
            'monitor.jvm.gc.overhead.warn',
            'monitor.jvm.gc.refresh_interval',
            'monitor.jvm.refresh_interval',
            'monitor.os.refresh_interval',
            'monitor.process.refresh_interval',
            'network.bind_host',
            'network.host',
            'network.publish_host',
            'network.server',
            'network.tcp.connect_timeout',
            'network.tcp.keep_alive',
            'network.tcp.keep_count',
            'network.tcp.keep_idle',
            'network.tcp.keep_interval',
            'network.tcp.no_delay',
            'network.tcp.receive_buffer_size',
            'network.tcp.reuse_address',
            'network.tcp.send_buffer_size',
            'no.model.state.persist',
            'node.attr.ml.machine_memory',
            'node.attr.ml.max_open_jobs',
            'node.attr.xpack.installed',
            'node.data',
            'node.enable_lucene_segment_infos_trace',
            'node.id.seed',
            'node.ingest',
            'node.local_storage',
            'node.master',
            'node.max_local_storage_nodes',
            'node.ml',
            'node.name',
            'node.pidfile',
            'node.portsfile',
            'node.processors',
            'node.store.allow_mmap',
            'node.voting_only',
            'path.data',
            'path.home',
            'path.logs',
            'path.repo',
            'path.shared_data',
            'pidfile',
            'plugin.mandatory',
            'processors',
            'reindex.remote.whitelist',
            'repositories.fs.chunk_size',
            'repositories.fs.compress',
            'repositories.fs.location',
            'repositories.url.allowed_urls',
            'repositories.url.supported_protocols',
            'repositories.url.url',
            'resource.reload.enabled',
            'resource.reload.interval.high',
            'resource.reload.interval.low',
            'resource.reload.interval.medium',
            'rest.action.multi.allow_explicit_index',
            'script.allowed_contexts',
            'script.allowed_types',
            'script.cache.expire',
            'script.cache.max_size',
            'script.painless.regex.enabled',
            'search.highlight.term_vector_multi_value',
            'search.keep_alive_interval',
            'search.remote.connect',
            'search.remote.connections_per_cluster',
            'search.remote.initial_connect_timeout',
            'search.remote.node.attr',
            'security.manager.filter_bad_defaults',
            'slm.history_index_enabled',
            'thread_pool.analyze.queue_size',
            'thread_pool.analyze.size',
            'thread_pool.estimated_time_interval',
            'thread_pool.fetch_shard_started.core',
            'thread_pool.fetch_shard_started.keep_alive',
            'thread_pool.fetch_shard_started.max',
            'thread_pool.fetch_shard_store.core',
            'thread_pool.fetch_shard_store.keep_alive',
            'thread_pool.fetch_shard_store.max',
            'thread_pool.flush.core',
            'thread_pool.flush.keep_alive',
            'thread_pool.flush.max',
            'thread_pool.force_merge.queue_size',
            'thread_pool.force_merge.size',
            'thread_pool.generic.core',
            'thread_pool.generic.keep_alive',
            'thread_pool.generic.max',
            'thread_pool.get.queue_size',
            'thread_pool.get.size',
            'thread_pool.listener.queue_size',
            'thread_pool.listener.size',
            'thread_pool.management.core',
            'thread_pool.management.keep_alive',
            'thread_pool.management.max',
            'thread_pool.refresh.core',
            'thread_pool.refresh.keep_alive',
            'thread_pool.refresh.max',
            'thread_pool.search.auto_queue_frame_size',
            'thread_pool.search.max_queue_size',
            'thread_pool.search.min_queue_size',
            'thread_pool.search.queue_size',
            'thread_pool.search.size',
            'thread_pool.search.target_response_time',
            'thread_pool.search_throttled.auto_queue_frame_size',
            'thread_pool.search_throttled.max_queue_size',
            'thread_pool.search_throttled.min_queue_size',
            'thread_pool.search_throttled.queue_size',
            'thread_pool.search_throttled.size',
            'thread_pool.search_throttled.target_response_time',
            'thread_pool.snapshot.core',
            'thread_pool.snapshot.keep_alive',
            'thread_pool.snapshot.max',
            'thread_pool.warmer.core',
            'thread_pool.warmer.keep_alive',
            'thread_pool.warmer.max',
            'thread_pool.write.queue_size',
            'thread_pool.write.size',
            'transform.task_thread_pool.queue_size',
            'transform.task_thread_pool.size',
            'transport.bind_host',
            'transport.compress',
            'transport.connect_timeout',
            'transport.connections_per_node.bulk',
            'transport.connections_per_node.ping',
            'transport.connections_per_node.recovery',
            'transport.connections_per_node.reg',
            'transport.connections_per_node.state',
            'transport.features.x-pack',
            'transport.host',
            'transport.netty.boss_count',
            'transport.netty.receive_predictor_max',
            'transport.netty.receive_predictor_min',
            'transport.netty.receive_predictor_size',
            'transport.netty.worker_count',
            'transport.ping_schedule',
            'transport.port',
            'transport.publish_host',
            'transport.publish_port',
            'transport.tcp.compress',
            'transport.tcp.connect_timeout',
            'transport.tcp.keep_alive',
            'transport.tcp.keep_count',
            'transport.tcp.keep_idle',
            'transport.tcp.keep_interval',
            'transport.tcp.no_delay',
            'transport.tcp.port',
            'transport.tcp.receive_buffer_size',
            'transport.tcp.reuse_address',
            'transport.tcp.send_buffer_size',
            'transport.tcp_no_delay',
            'transport.type',
            'transport.type.default',
            'xpack.ccr.ccr_thread_pool.queue_size',
            'xpack.ccr.ccr_thread_pool.size',
            'xpack.ccr.enabled',
            'xpack.data_frame.enabled',
            'xpack.enrich.enabled',
            'xpack.flattened.enabled',
            'xpack.graph.enabled',
            'xpack.http.default_connection_timeout',
            'xpack.http.default_read_timeout',
            'xpack.http.max_response_size',
            'xpack.http.proxy.host',
            'xpack.http.proxy.port',
            'xpack.http.proxy.scheme',
            'xpack.ilm.enabled',
            'xpack.license.self_generated.type',
            'xpack.license.upload.types',
            'xpack.logstash.enabled',
            'xpack.ml.autodetect_process',
            'xpack.ml.datafeed_thread_pool.core',
            'xpack.ml.datafeed_thread_pool.keep_alive',
            'xpack.ml.datafeed_thread_pool.max',
            'xpack.ml.enabled',
            'xpack.ml.inference_model.cache_size',
            'xpack.ml.inference_model.time_to_live',
            'xpack.ml.job_comms_thread_pool.core',
            'xpack.ml.job_comms_thread_pool.keep_alive',
            'xpack.ml.job_comms_thread_pool.max',
            'xpack.ml.min_disk_space_off_heap',
            'xpack.ml.utility_thread_pool.core',
            'xpack.ml.utility_thread_pool.keep_alive',
            'xpack.ml.utility_thread_pool.max',
            'xpack.monitoring.enabled',
            'xpack.notification.email.html.sanitization.allow',
            'xpack.notification.email.html.sanitization.disallow',
            'xpack.notification.email.html.sanitization.enabled',
            'xpack.notification.reporting.interval',
            'xpack.notification.reporting.retries',
            'xpack.rollup.enabled',
            'xpack.rollup.task_thread_pool.queue_size',
            'xpack.rollup.task_thread_pool.size',
            'xpack.security.audit.enabled',
            'xpack.security.authc.anonymous.authz_exception',
            'xpack.security.authc.anonymous.roles',
            'xpack.security.authc.anonymous.username',
            'xpack.security.authc.api_key.cache.hash_algo',
            'xpack.security.authc.api_key.cache.max_keys',
            'xpack.security.authc.api_key.cache.ttl',
            'xpack.security.authc.api_key.delete.interval',
            'xpack.security.authc.api_key.delete.timeout',
            'xpack.security.authc.api_key.enabled',
            'xpack.security.authc.api_key.hashing.algorithm',
            'xpack.security.authc.password_hashing.algorithm',
            'xpack.security.authc.reserved_realm.enabled',
            'xpack.security.authc.run_as.enabled',
            'xpack.security.authc.success_cache.enabled',
            'xpack.security.authc.success_cache.expire_after_access',
            'xpack.security.authc.success_cache.size',
            'xpack.security.authc.token.delete.interval',
            'xpack.security.authc.token.delete.timeout',
            'xpack.security.authc.token.enabled',
            'xpack.security.authc.token.thread_pool.queue_size',
            'xpack.security.authc.token.thread_pool.size',
            'xpack.security.authc.token.timeout',
            'xpack.security.authz.store.roles.cache.max_size',
            'xpack.security.authz.store.roles.field_permissions.cache.max_size_in_bytes',
            'xpack.security.authz.store.roles.index.cache.max_size',
            'xpack.security.authz.store.roles.index.cache.ttl',
            'xpack.security.authz.store.roles.negative_lookup_cache.max_size',
            'xpack.security.automata.cache.enabled',
            'xpack.security.automata.cache.size',
            'xpack.security.automata.cache.ttl',
            'xpack.security.automata.max_determinized_states',
            'xpack.security.dls.bitset.cache.size',
            'xpack.security.dls.bitset.cache.ttl',
            'xpack.security.dls_fls.enabled',
            'xpack.security.enabled',
            'xpack.security.encryption.algorithm',
            'xpack.security.encryption_key.algorithm',
            'xpack.security.encryption_key.length',
            'xpack.security.filter.always_allow_bound_address',
            'xpack.security.fips_mode.enabled',
            'xpack.security.http.ssl.enabled',
            'xpack.security.ssl.diagnose.trust',
            'xpack.security.transport.ssl.enabled',
            'xpack.security.user',
            'xpack.slm.enabled',
            'xpack.sql.enabled',
            'xpack.transform.enabled',
            'xpack.vectors.enabled',
            'xpack.watcher.actions.bulk.default_timeout',
            'xpack.watcher.actions.index.default_timeout',
            'xpack.watcher.bulk.actions',
            'xpack.watcher.bulk.concurrent_requests',
            'xpack.watcher.bulk.flush_interval',
            'xpack.watcher.bulk.size',
            'xpack.watcher.enabled',
            'xpack.watcher.encrypt_sensitive_data',
            'xpack.watcher.execution.default_throttle_period',
            'xpack.watcher.execution.scroll.size',
            'xpack.watcher.execution.scroll.timeout',
            'xpack.watcher.index.rest.direct_access',
            'xpack.watcher.input.search.default_timeout',
            'xpack.watcher.internal.ops.bulk.default_timeout',
            'xpack.watcher.internal.ops.index.default_timeout',
            'xpack.watcher.internal.ops.search.default_timeout',
            'xpack.watcher.stop.timeout',
            'xpack.watcher.thread_pool.queue_size',
            'xpack.watcher.thread_pool.size',
            'xpack.watcher.transform.search.default_timeout',
            'xpack.watcher.trigger.schedule.ticker.tick_interval',
            'xpack.watcher.watch.scroll.size',
        ];
    }
}
